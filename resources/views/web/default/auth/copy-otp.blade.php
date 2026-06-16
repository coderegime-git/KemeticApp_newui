<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Copy OTP</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #111111;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: Arial, sans-serif;
        }
        .card {
            background: #1a1a1a;
            border: 1px solid #d4af37;
            border-radius: 16px;
            padding: 40px 50px;
            text-align: center;
        }
        .otp {
            font-size: 42px;
            font-weight: bold;
            letter-spacing: 12px;
            color: #fff;
            background: linear-gradient(45deg, #b8962e, #d4af37);
            padding: 16px 32px;
            border-radius: 10px;
            display: inline-block;
            margin: 20px 0;
        }
        .status {
            font-size: 16px;
            margin-top: 16px;
            height: 24px;
            color: #4CAF50;
            font-weight: bold;
        }
        .hint {
            font-size: 13px;
            color: #888;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="card">
        <p style="color:#aaaaaa; font-size:14px;">Your One-Time Password</p>

        <div class="otp" id="otp">{{ $otp }}</div>

        <div class="status" id="status"></div>
        <p class="hint" id="hint">Copying automatically...</p>
    </div>

    <script>
        var otp = document.getElementById('otp').innerText.trim();
        var status = document.getElementById('status');
        var hint = document.getElementById('hint');

        function copyDone() {
            status.innerText = '✅ OTP Copied to Clipboard!';
            hint.innerText = 'You can now go back and paste it.';
        }

        function copyFallback() {
            // Select the OTP text manually as last resort
            var range = document.createRange();
            range.selectNode(document.getElementById('otp'));
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
            try {
                document.execCommand('copy');
                copyDone();
            } catch(e) {
                status.style.color = '#d4af37';
                status.innerText = '👆 Tap and hold the code to copy';
                hint.innerText = '';
            }
        }

        // Auto copy on page load
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(otp)
                .then(copyDone)
                .catch(copyFallback);
        } else {
            copyFallback();
        }
    </script>
</body>
</html>