**Usage**

- Activate “PPV Obfuscate Uploads” in WP Admin → Plugins.
- When you upload a .mp4 (or other recognized video), WordPress automatically renames it to a random hash and places it in /ppv/.
- If you upload images or PDFs, they still go to /wp-content/uploads/ as normal.

**Limitations & Tips**

- If you want to store videos outside the doc root, change $custom_dir to something like /home/runcloud/webapps/contactcustody/private-ppv, but you must then serve them via a script (see the .htaccess + check_access.php snippet later).
- Make sure /ppv/ or that custom path is writable by the webserver user.
