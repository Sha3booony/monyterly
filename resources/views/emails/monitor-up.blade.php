<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background-color:#0a0a0f;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#0a0a0f;padding:40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#16161f;border-radius:16px;overflow:hidden;border:1px solid #2a2a3a;">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #00ff88, #00aaff);padding:32px;text-align:center;">
                            <div style="font-size:32px;margin-bottom:8px;">🟢</div>
                            <div style="font-family:'Courier New',monospace;font-size:24px;font-weight:800;color:#000;letter-spacing:3px;">
                                RECOVERED: SITE UP
                            </div>
                            <div style="color:rgba(0,0,0,0.6);font-size:14px;margin-top:8px;">
                                Monitorly Recovery Notification
                            </div>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:32px;">
                            <div style="font-size:18px;font-weight:600;color:#e8e8ef;margin-bottom:20px;">
                                ✅ {{ $monitor->name }} is back online!
                            </div>

                            <div style="color:#8888aa;font-size:14px;line-height:1.8;margin-bottom:24px;">
                                Great news! Your website has recovered and is now responding normally.
                                @if($issue)
                                The incident has been automatically resolved.
                                @endif
                            </div>

                            <!-- Details Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#111118;border-radius:12px;border:1px solid #2a2a3a;margin-bottom:24px;">
                                <tr>
                                    <td style="padding:20px;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding:8px 0;color:#8888aa;font-size:13px;width:140px;">Website</td>
                                                <td style="padding:8px 0;color:#e8e8ef;font-size:13px;font-weight:600;">{{ $monitor->name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;color:#8888aa;font-size:13px;">URL</td>
                                                <td style="padding:8px 0;font-size:13px;">
                                                    <a href="{{ $monitor->url }}" style="color:#00aaff;text-decoration:none;font-family:'Courier New',monospace;">{{ $monitor->url }}</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;color:#8888aa;font-size:13px;">Status</td>
                                                <td style="padding:8px 0;color:#00ff88;font-size:13px;font-weight:700;">● ONLINE</td>
                                            </tr>
                                            @if($monitor->response_time)
                                            <tr>
                                                <td style="padding:8px 0;color:#8888aa;font-size:13px;">Response Time</td>
                                                <td style="padding:8px 0;color:#00ff88;font-size:13px;font-family:'Courier New',monospace;">{{ $monitor->response_time }}ms</td>
                                            </tr>
                                            @endif
                                            @if($issue)
                                            <tr>
                                                <td style="padding:8px 0;color:#8888aa;font-size:13px;">Downtime</td>
                                                <td style="padding:8px 0;color:#ffaa00;font-size:13px;font-family:'Courier New',monospace;">{{ $issue->formatted_duration }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;color:#8888aa;font-size:13px;">Issue ID</td>
                                                <td style="padding:8px 0;color:#00ff88;font-family:'Courier New',monospace;font-size:13px;">#{{ $issue->id }} (Resolved)</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/dashboard') }}"
                                           style="display:inline-block;padding:14px 32px;background:linear-gradient(135deg,#00ff88,#00aaff);color:#000;text-decoration:none;border-radius:10px;font-weight:700;font-size:14px;letter-spacing:0.5px;">
                                            Open Dashboard →
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:24px 32px;border-top:1px solid #2a2a3a;text-align:center;">
                            <div style="font-family:'Courier New',monospace;font-size:14px;font-weight:700;color:#00ff88;letter-spacing:2px;margin-bottom:8px;">MONITORLY</div>
                            <div style="color:#55556a;font-size:12px;">Website Monitoring & Incident Management</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
