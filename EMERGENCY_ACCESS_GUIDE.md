# Emergency Admin Access Guide

## Overview

This system includes an **Emergency Access** feature that allows administrators to regain access when the system is locked out due to:
- System Shutdown being enabled
- All Sign-ins being blocked

## ⚠️ Important Warning

This feature should **ONLY** be used in genuine lockout situations. Using it unnecessarily bypasses important security controls.

---

## How Lockout Happens

### Scenario 1: System Shutdown Lockout
When an admin enables **System Shutdown** from the Security page:
- All authenticated users are immediately logged out
- All subsequent requests show a shutdown page
- Even admins cannot access any part of the system
- Login attempts succeed but immediately redirect to shutdown page

### Scenario 2: Block All Sign-ins Lockout
When an admin enables **Block All Sign-ins**:
- All login attempts are rejected with error message
- No new sessions can be created
- Existing sessions remain active until they expire
- If all admins log out, complete lockout occurs

---

## Emergency Access Procedure

### Step 1: Access Emergency Login Page

When locked out, navigate directly to:
```
http://your-domain.com/admin/emergency-login
```

**Note:** This URL is always accessible, even during system shutdown or sign-in blocks.

### Step 2: Prepare Your Credentials

You will need:
1. **Admin Email** - Your administrator email address
2. **Admin Password** - Your normal login password
3. **Emergency Access Code** - Found in your `.env` file

### Step 3: Find Emergency Code

Locate your `.env` file in the project root directory and find:
```env
EMERGENCY_ADMIN_PASSWORD=ChangeThisEmergencyPassword123!
EMERGENCY_ACCESS_DURATION_MINUTES=60
```

**Important:** Change the default password immediately after first use!

### Step 4: Login

1. Enter your admin email and password
2. Enter the emergency code from `.env`
3. Click "Grant Emergency Access"

If successful, you'll be redirected to the admin dashboard with a **60-minute time-limited session**.

---

## Emergency Session Features

### Visual Indicators

When logged in with emergency access, you'll see:
1. **Red banner** at the top of the sidebar showing "EMERGENCY ACCESS ACTIVE"
2. **Countdown timer** showing when the session expires
3. **Badge** next to your user role saying "EMERGENCY"
4. **Security button** (shield icon) will pulse to indicate emergency mode

### Session Duration

- Default: **60 minutes** (configurable via `.env`)
- Session automatically expires after time limit
- Cannot be extended beyond the configured duration
- After expiry, you'll be logged out and must use emergency login again

### What You Can Do

During emergency access, you can:
- ✅ Access all admin features normally
- ✅ Toggle System Shutdown OFF
- ✅ Toggle Block Sign-ins OFF
- ✅ Manage users and settings
- ✅ View all system data

### What You Cannot Do

During emergency access, you **cannot**:
- ❌ Disable emergency mode manually (it auto-expires)
- ❌ Change the emergency password (must edit `.env` directly)
- ❌ Bypass normal authentication (still need valid admin credentials)

---

## After Regaining Access

### Immediate Actions

Once logged in via emergency access:

1. **Turn OFF System Shutdown** (if enabled)
   - Go to: `/admin/security`
   - Toggle "System Shutdown" to OFF

2. **Turn OFF Block All Sign-ins** (if enabled)
   - Same Security page
   - Toggle "Block All Sign-ins" to OFF

3. **Verify Normal Login Works**
   - Log out completely
   - Try logging in normally (without emergency code)
   - Ensure system is accessible

### Security Checklist

- [ ] System Shutdown is OFF
- [ ] Block All Sign-ins is OFF
- [ ] All admin accounts are accessible
- [ ] Normal user logins work
- [ ] Emergency password has been changed in `.env`
- [ ] Review security logs for suspicious activity

---

## Changing Emergency Password

### Step 1: Edit `.env` File

Open your `.env` file and update:
```env
EMERGENCY_ADMIN_PASSWORD=YourNewStrongPasswordHere123!
```

**Password Requirements:**
- Minimum 12 characters
- Include uppercase, lowercase, numbers, and symbols
- Never use the default password

### Step 2: Clear Configuration Cache

If using config caching, run:
```bash
php artisan config:clear
```

### Step 3: Save Securely

Store the emergency password in a secure password manager. Only share with:
- Other system administrators
- Technical support personnel

---

## Configuring Emergency Access Duration

By default, emergency sessions last **60 minutes**. To change:

### Edit `.env` File
```env
EMERGENCY_ACCESS_DURATION_MINUTES=120
```

Valid values: `15` to `1440` minutes (15 minutes to 24 hours)

**Recommendation:** Keep it short (60 minutes) for security.

---

## Emergency Access Logging

All emergency access attempts are **automatically logged** in the system:

### Log Entries Include:
- Admin username
- IP address
- Timestamp
- Success/failure status
- Session duration

### View Logs
Navigate to: `/admin/logs` and filter by:
- Title: "Emergency Access Used"
- Title: "System Control" (when shutdown/block toggled)

---

## Troubleshooting

### "Invalid emergency access code"
- Double-check the code in your `.env` file
- Ensure no extra spaces or typos
- Remember it's case-sensitive

### "Emergency session has expired"
- Session time limit reached
- Log in again via emergency login
- Consider increasing duration in `.env` if needed

### "Emergency access is restricted to administrators only"
- You're trying to log in with a non-admin account
- Only users with `levelStatus = 'Admin'` can use emergency access

### "System is shut down" page still shows after emergency login
- Clear browser cookies and cache
- Ensure you're accessing admin routes (not user routes)
- Check that emergency session is active (look for banner in sidebar)

### Can't find `/admin/emergency-login` URL
- Ensure routes are registered (check `routes/web.php`)
- Clear route cache: `php artisan route:clear`
- Verify middleware isn't blocking the route

---

## Best Practices

### Before Enabling Shutdown/Block
1. **Ensure at least 2 admins are online** before toggling
2. **Test emergency access** in a non-critical environment
3. **Document emergency password** in secure location
4. **Notify all admins** before making system-wide changes

### After Emergency Use
1. **Change emergency password** immediately
2. **Review logs** for unauthorized attempts
3. **Audit admin accounts** for compromised credentials
4. **Update team** on what happened and how to prevent

### Regular Maintenance
- Test emergency access quarterly
- Rotate emergency password every 6 months
- Keep `.env` file permissions restricted (chmod 600)
- Monitor logs for unusual emergency access patterns

---

## Technical Details

### Routes Added
```php
GET  /admin/emergency-login          # Show emergency login form
POST /admin/emergency-login          # Process emergency login
POST /admin/emergency-extend         # Extend session (API)
```

### Session Variables Set
```php
session('emergency_access')       => true
session('emergency_login_at')     => ISO timestamp
session('emergency_expires_at')   => ISO timestamp (now + duration)
```

### Database Logging
Emergency access is logged in the `logs` table:
- `title` = "Emergency Access Used"
- `description` = "Admin Name used emergency login from IP: x.x.x.x"
- `status` = "done"

---

## Support

If you're unable to regain access after following this guide:

1. **Database Reset** (last resort):
   ```sql
   UPDATE system SET system_shutdown = 0, block_signins = 0 WHERE id = 1;
   ```

2. **Contact Technical Support**
   - Email: system@lerumapos.com
   - Phone: +255 123 456 789
   - Have your `.env` emergency password ready

---

## Version History

- **v1.0** (2026-04-28): Initial emergency access implementation
  - Emergency login bypass
  - Time-limited sessions
  - Visual indicators
  - Comprehensive logging

---

**Last Updated:** April 28, 2026  
**Applies to:** Elite POS System v3.0+