const {
    default: makeWASocket,
    useMultiFileAuthState
} = require('@whiskeysockets/baileys')

const express = require('express')
const qrcode = require('qrcode-terminal')
const cors = require('cors')
const process = require('process')

const app = express()
app.use(express.json())
app.use(cors())

// Simple config
const PORT = process.env.PORT ? parseInt(process.env.PORT, 10) : 5000

let sock = null
let reconnectAttempts = 0
const MAX_RECONNECT_BACKOFF = 30000 // 30s

function backoffDelay(attempt) {
    // exponential backoff with cap
    const delay = Math.min(1000 * Math.pow(2, attempt), MAX_RECONNECT_BACKOFF)
    return delay
}

async function connectWA() {
    try {
        const { state, saveCreds } = await useMultiFileAuthState('./baileys_auth')

        sock = makeWASocket({
            printQRInTerminal: false,
            auth: state
        })

        // persist credentials
        sock.ev.on('creds.update', saveCreds)

        // Reset reconnect attempts on successful connection
        sock.ev.on('connection.update', (update) => handleConnectionUpdate(update))

        // Incoming messages (log only, no auto-reply)
        sock.ev.on('messages.upsert', async ({ messages }) => {
            try {
                const msg = messages?.[0]
                if (!msg) return
                if (msg.key?.fromMe) return

                const text = msg.message?.conversation || msg.message?.extendedTextMessage?.text || ''
                console.log('[WA] Incoming message from', msg.key.remoteJid, ':', text)
                // Pesan dicatat tetapi TIDAK ada balasan otomatis
            } catch (err) {
                console.error('[WA] messages.upsert handler error:', err?.message || err)
            }
        })

        console.log('[WA] Socket initialized')
    } catch (err) {
        console.error('[WA] connectWA error:', err?.message || err)
        scheduleReconnect()
    }
}

function handleConnectionUpdate({ connection, lastDisconnect, qr, isNewLogin }) {
    if (qr) {
        console.log('[WA] SCAN QR DI TERMINAL!')
        qrcode.generate(qr, { small: true })
    }

    if (connection === 'open') {
        console.log('[WA] CONNECTED')
        reconnectAttempts = 0
    }

    if (connection === 'close') {
        const reason = lastDisconnect && lastDisconnect.error ? (lastDisconnect.error.output?.statusCode || lastDisconnect.error.message) : 'unknown'
        console.warn('[WA] connection closed, reason:', reason)
        scheduleReconnect()
    }
}

function scheduleReconnect() {
    reconnectAttempts = Math.min(reconnectAttempts + 1, 10)
    const delay = backoffDelay(reconnectAttempts)
    console.log(`[WA] Reconnecting in ${delay} ms (attempt ${reconnectAttempts})`)
    setTimeout(() => {
        connectWA()
    }, delay)
}

// start connection
connectWA()

// Utilities
function formatToJid(number) {
    // If already looks like a JID, return as-is
    if (typeof number !== 'string') return null
    if (number.includes('@')) return number

    // Remove non-numeric chars
    const digits = number.replace(/[^0-9]/g, '')
    if (!digits) return null

    // If number length seems short, user must supply country code
    // Expect number to include country code (e.g., 6281234... for Indonesia)
    return digits + '@s.whatsapp.net'
}

// Routes
app.get('/', (req, res) => {
    return res.json({ status: 'ok', wa_connected: !!sock })
})

// Helpful for browser users: explain that /send is POST-only
app.get('/send', (req, res) => {
    return res.status(405).send('Method GET not allowed. Use POST /send with JSON { number, message }')
})

app.post('/send', async (req, res) => {
    try {
        const { number, message } = req.body || {}

        if (!number || !message) {
            return res.status(400).json({ error: 'number & message required' })
        }

        const jid = formatToJid(number)
        if (!jid) return res.status(400).json({ error: 'invalid number format' })

        if (!sock) return res.status(503).json({ error: 'wa socket not connected yet' })

        // enqueue send asynchronously to avoid blocking the HTTP request
        sock.sendMessage(jid, { text: message })
            .then(() => console.log('[API] Message queued for', jid))
            .catch((err) => console.error('[API] async send error', err?.message || err))

        // respond immediately to caller
        return res.status(202).json({ accepted: true, to: jid })
    } catch (err) {
        console.error('[API] /send error:', err?.message || err)
        return res.status(500).json({ error: err?.message || 'server error' })
    }
})

// Global error handlers & process signals
process.on('unhandledRejection', (reason) => {
    console.error('[process] unhandledRejection', reason)
})

process.on('uncaughtException', (err) => {
    console.error('[process] uncaughtException', err)
})

process.on('SIGINT', () => {
    console.log('[process] SIGINT, exiting')
    process.exit(0)
})

app.listen(PORT, () => {
    console.log(`WA API running on port ${PORT}`)
})
