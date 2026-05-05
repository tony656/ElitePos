# Face Recognition Security Feature

## Overview
This feature adds continuous face identification to the system. When enabled, all logged-in devices will continuously scan for faces. If an unknown face is visible for more than 5 seconds, the user will be automatically logged out.

## Components

### 1. Database Migrations
- `user_face_encodings` - Stores facial encodings for each user
- `face_verification_logs` - Logs all verification attempts
- System table columns: `face_recognition_enabled`, `face_verification_timeout`, `require_face_registration`
- Active sessions table columns: `face_verified`, `last_face_check`, `failed_face_attempts`, `face_verification_expires_at`

### 2. Models
- `UserFaceEncoding` - Eloquent model for face encodings

### 3. Controllers
- `FaceRecognitionController` - Handles face registration, verification, and management
- `SystemController` - Added `toggleFaceRecognition()` method

### 4. Middleware
- `FaceRecognitionMiddleware` - Continuously checks face verification status

### 5. JavaScript
- `public/js/face-recognition.js` - Client-side face detection using face-api.js

### 6. Views
- `resources/views/user/face-registration.blade.php` - User face registration page
- `resources/views/admin/face-registration.blade.php` - Admin face registration page
- `resources/views/user/face-verification.blade.php` - Face verification page
- Updated `resources/views/user/security.blade.php` - Added face recognition management section
- Updated `resources/views/admin/security.blade.php` - Added toggle and management section

## Installation

1. Run the migration to create tables:
```bash
php artisan migrate
```

2. The system is now ready. No additional dependencies required - face-api.js is loaded from CDN.

## Usage

### For Administrators

1. Go to **Security** page
2. Toggle **Face Recognition Security** switch to enable/disable globally
3. Click **Register My Face** to register your facial data
4. View all registered encodings in the management section

### For Regular Users

1. Go to **Security** page
2. Click **Register My Face** to register facial data
3. Follow prompts to capture face using camera

## How It Works

1. **Registration**: User's face is captured and encoded into a 128-dimensional vector using face-api.js
2. **Storage**: Encodings are stored in `user_face_encodings` table as JSON
3. **Continuous Verification**: Middleware checks every second if face is verified
4. **Matching**: Current face is compared against stored encodings using Euclidean distance
5. **Threshold**: 60% confidence required for verification
6. **Auto-logout**: If unknown face visible for >5 seconds, user is logged out

## Configuration

System settings can be adjusted in `system` table:
- `face_recognition_enabled` - Global on/off switch
- `face_verification_timeout` - Seconds before auto-logout (default: 5)
- `require_face_registration` - Force users to register faces

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /face/register | Show registration page |
| POST | /face/register | Register face encoding |
| POST | /face/verify | Verify current face |
| GET | /face/encodings | Get user's encodings |
| DELETE | /face/encoding/{id} | Delete encoding |
| GET | /face/logs | Get verification logs |
| GET | /face/verify-page | Show verification page |

## Security Notes

- Face encodings are stored as numerical arrays, not images
- All verification attempts are logged
- Multiple encodings per user supported (different devices/angles)
- Inactive encodings can be kept for audit purposes
- Works completely offline after initial library load

## Browser Compatibility

Requires modern browser with:
- MediaDevices API (camera access)
- WebAssembly support (face-api.js)
- Recommended: Chrome, Firefox, Edge, Safari (latest versions)

## Troubleshooting

**Camera not working**: Ensure HTTPS or localhost, grant camera permissions

**Face not detected**: Ensure good lighting, face clearly visible, no obstructions

**Verification fails**: Try re-registering face in better lighting conditions

**Performance**: face-api.js loads from CDN (~2MB), initial load may take a few seconds