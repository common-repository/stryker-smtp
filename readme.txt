=== Stryker SMTP ===
Contributors: DarrenBelow
Tags: smtp, email, smtp email, email settings, wp smtp
Requires at least: 5.0
Tested up to: 6.6.1
Requires PHP: 7.2
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Stryker SMTP sends all WordPress emails through an SMTP server of your choice. Configure SMTP credentials from the wp-admin settings page.

== Description ==

Stryker SMTP is a simple plugin that ensures all outgoing emails from your WordPress site are sent using an SMTP server. You can set up the SMTP host, port, encryption, email credentials, and sender name right from your WordPress admin dashboard. It also includes a test email feature to verify the configuration.

### Key Features:

* Send all emails through an SMTP server of your choice.
* Compatible with Zoho Mail, Gmail, and other SMTP services.
* Set your SMTP host, port, encryption type, and email credentials in the admin settings.
* Customize the sender's name and email address.
* Send a test email to ensure the SMTP settings are correct.
* Secure handling of credentials.
* Supports SSL and TLS encryption.

== Installation ==

1. Upload the `stryker-smtp` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to the **Settings > Stryker SMTP** page to configure the plugin.
4. Enter your SMTP credentials, including the host, port, encryption, and sender name.
5. Save the settings and use the "Send Test Email" feature to verify the SMTP connection.

== Frequently Asked Questions ==

= How do I configure the plugin for Zoho SMTP? =

To configure Stryker SMTP for Zoho:
1. Set the SMTP Host to `smtp.zoho.com`.
2. Set the SMTP Port to `465` for SSL or `587` for TLS.
3. Choose your encryption method, either SSL or TLS.
4. Enter your Zoho email address and password in the SMTP credentials.

= Can I use Gmail as the SMTP server? =

Yes, you can configure Stryker SMTP to use Gmail's SMTP server. Use the following settings:
- SMTP Host: `smtp.gmail.com`
- SMTP Port: `465` for SSL or `587` for TLS
- Encryption: SSL or TLS
- SMTP Username: Your Gmail email address
- SMTP Password: Your Gmail account password or App Password (if 2FA is enabled)

= How do I send a test email? =

After entering your SMTP credentials, use the **Send Test Email** section in the settings page to send a test email. If the email is delivered successfully, your SMTP setup is working correctly.

= Is my SMTP password stored securely? =

Yes, the plugin stores your SMTP password securely in the WordPress database, similar to other sensitive WordPress settings.

== Screenshots ==

1. **SMTP Settings Page** - Configure the SMTP host, port, encryption, and email credentials.
2. **Send Test Email** - Test your SMTP settings by sending a test email.

== License ==

This plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any later version.

This plugin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this plugin. If not, see [https://www.gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html).
