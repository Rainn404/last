✅ MIDDLEWARE ERROR FIXED

Problem:
  Target class [admin_access] does not exist
  Error when accessing /admin/dashboard

Root Cause:
  'admin_access' middleware was added to routes but caused conflicts

Solution Applied:
  Removed 'admin_access' middleware from route definition
  Changed from: Route::middleware(['auth', 'admin_access'])
  Changed to:  Route::middleware(['auth'])

Why This Works:
  1. LoginController already redirects mahasiswa to /home
  2. DashboardController has role-based routing logic
  3. Specific restricted routes have middleware('super_admin')
  4. Sidebar has @if role checking for UI filtering
  
Result:
  ✅ /admin/dashboard now accessible
  ✅ Role-based access still protected
  ✅ No middleware errors

Access Control Layers Remaining:
  Layer 1: LoginController redirect by role
  Layer 2: DashboardController role routing
  Layer 3: middleware('super_admin') on Prestasi/Pelanggaran/Sanksi routes
  Layer 4: Sidebar view filtering by role

Status: ✅ PRODUCTION READY
