# SavanaHost SSH Deployment Guide for Buniafrika

This guide provides end-to-end instructions for deploying Buniafrika to SavanaHost cPanel hosting using SSH and Rsync via GitHub Actions.

---

## 1. Hosting Architecture Overview

On SavanaHost, the frontend and backend applications reside under a single unified parent directory inside your cPanel home directory:

```text
/home/CPANEL_USERNAME/
│
├── buniafrika/                          <-- SSH_DEPLOY_PATH (Unified Destination)
│   ├── buniafrika/                      # Frontend Application (HTML, CSS, JS, public assets)
│   └── buniafrika.co.ke/                # Private Backend (API, business logic, DB connectors)
│
└── secrets/                             # Production Secrets (Outside public web roots)
    └── buniafrika.env (or .env)         # Environment variables (DB credentials, SMTP, URLs)
```

> [!IMPORTANT]
> **Secrets Isolation**: Production environment credentials (`secrets/buniafrika.env` or `secrets/.env`) must **never** be placed inside web-accessible directories or committed to Git. The deployment workflow automatically excludes `.env` and `secrets/` to prevent overwriting your live server configuration.

---

## 2. Enabling SSH Access in SavanaHost cPanel

1. Log in to your **SavanaHost cPanel** account.
2. In the search bar or under the **Security** section, click **SSH Access** (or **Terminal**).
3. If SSH access is not enabled by default, click **Manage SSH Keys** or submit a support ticket to SavanaHost support requesting **Jailed SSH Shell access** for your cPanel account.
4. Note your cPanel username (displayed in the top-right corner or general information sidebar of cPanel).

---

## 3. Generating and Configuring an SSH Key Pair

You can generate the SSH key pair either directly inside cPanel or locally on your machine.

### Method A: Generating via Terminal / Command Line (Recommended)

1. Open your local terminal and run:
   ```bash
   ssh-keygen -t ed25519 -C "github-actions-deploy@buniafrika" -f ~/.ssh/buniafrika_deploy_key
   # Or using RSA 4096-bit if your host requires RSA:
   # ssh-keygen -t rsa -b 4096 -C "github-actions-deploy@buniafrika" -f ~/.ssh/buniafrika_deploy_key
   ```
   *When prompted for a passphrase, press **Enter twice** to create a key without a passphrase so GitHub Actions can authenticate automatically.*

2. This produces two files:
   - `buniafrika_deploy_key`: The **private key** (used in GitHub Secrets).
   - `buniafrika_deploy_key.pub`: The **public key** (installed on SavanaHost).

3. Copy the public key to SavanaHost:
   - In cPanel, navigate to **SSH Access** &rarr; **Manage SSH Keys** &rarr; **Import Key**.
   - Paste the contents of `buniafrika_deploy_key.pub` into the **Public Key** box.
   - Click **Import**.
   - Under **Public Keys**, find the newly imported key, click **Manage**, and then click **Authorize**.
   - Alternatively, append the public key line into `/home/CPANEL_USERNAME/.ssh/authorized_keys` with permissions `chmod 600 ~/.ssh/authorized_keys`.

---

## 4. Identifying the Absolute Deployment Path

1. Log in to cPanel and open the **Terminal** or inspect the **General Information** pane on the right-hand side.
2. Run `pwd` in the terminal to find your home directory, e.g.:
   ```text
   /home/CPANEL_USERNAME
   ```
3. Your unified deployment path is:
   ```text
   /home/CPANEL_USERNAME/buniafrika/
   ```
4. Verify directory permissions:
   ```bash
   mkdir -p /home/CPANEL_USERNAME/buniafrika
   chmod 755 /home/CPANEL_USERNAME/buniafrika
   ```

---

## 5. Configuring GitHub Repository Secrets

In your GitHub repository:
1. Navigate to **Settings** &rarr; **Secrets and variables** &rarr; **Actions**.
2. Click **New repository secret** and add the following 5 secrets:

| Secret Name | Description | Example / Format |
| :--- | :--- | :--- |
| `SSH_HOST` | Hostname or server IP address of SavanaHost | `server123.savanahost.com` or `197.xxx.xxx.xxx` |
| `SSH_USERNAME` | Your cPanel account username | `cpaneluser` |
| `SSH_PRIVATE_KEY` | Entire private key file (`buniafrika_deploy_key`) including header and footer | `-----BEGIN OPENSSH PRIVATE KEY----- ... -----END OPENSSH PRIVATE KEY-----` |
| `SSH_PORT` | SSH port configured on SavanaHost (standard: `22`) | `22` (or custom cPanel port e.g. `2222`) |
| `SSH_DEPLOY_PATH` | Absolute path to unified destination directory | `/home/CPANEL_USERNAME/buniafrika/` |

> [!CAUTION]
> Ensure there are no trailing whitespace characters or accidental line breaks when pasting `SSH_PRIVATE_KEY`.

---

## 6. Server Production Environment Setup (`secrets/`)

Before initiating deployment, create your production secrets file on SavanaHost:

1. Create the secrets directory in your cPanel user root:
   ```bash
   mkdir -p /home/CPANEL_USERNAME/secrets
   chmod 700 /home/CPANEL_USERNAME/secrets
   ```

2. Create `/home/CPANEL_USERNAME/secrets/buniafrika.env` (or `.env`):
   ```ini
   # Database Configuration (MySQL / MariaDB on SavanaHost)
   DB_HOST=localhost
   DB_USER=cpaneluser_dbuser
   DB_PASS=YourSecureProductionPasswordHere
   DB_NAME=cpaneluser_buniafrika
   DB_PORT=3306

   # Mail Configuration (SMTP)
   SMTP_HOST=mail.buniafrika.co.ke
   SMTP_PORT=465
   SMTP_USERNAME=info@buniafrika.co.ke
   SMTP_PASSWORD=YourSmtpPasswordHere
   SMTP_FROM=info@buniafrika.co.ke

   # Production Application URLs
   BACKEND_API_URL=https://buniafrika.co.ke
   FRONTEND_URL=https://buniafrika.co.ke
   ```

3. Secure the secrets file permissions:
   ```bash
   chmod 600 /home/CPANEL_USERNAME/secrets/buniafrika.env
   ```

---

## 7. Executing a Deployment

### Automated Trigger
Every push to the `main` branch automatically:
1. Validates directory structure and secrets isolation.
2. Checks PHP syntax across all frontend and backend scripts.
3. Validates JavaScript syntax.
4. Verifies bootstrap loaders.
5. Deploys updated frontend and backend files via Rsync over SSH.

### Manual Trigger (Workflow Dispatch)
1. Go to the **Actions** tab on GitHub.
2. Select **Deploy Buniafrika to SavanaHost (SSH)** in the left sidebar.
3. Click **Run workflow**, choose the `main` branch, and click **Run workflow**.

---

## 8. Troubleshooting Common SSH & Deployment Issues

### Error: `Permission denied (publickey)`
- **Check Key Authorization**: In cPanel &rarr; **SSH Access** &rarr; **Manage SSH Keys**, confirm that the imported public key status is **Authorized**.
- **Check Key Content**: Ensure `SSH_PRIVATE_KEY` in GitHub Secrets matches the exact private key corresponding to the authorized public key, including headers:
  ```text
  -----BEGIN OPENSSH PRIVATE KEY-----
  ...
  -----END OPENSSH PRIVATE KEY-----
  ```
- **Check File Permissions on Server**:
  ```bash
  chmod 700 /home/CPANEL_USERNAME/.ssh
  chmod 600 /home/CPANEL_USERNAME/.ssh/authorized_keys
  chmod 755 /home/CPANEL_USERNAME
  ```

### Error: `Connection refused` or `Connection timed out`
- **Verify Port**: Some hosting providers use non-standard SSH ports (e.g. `2222` instead of `22`). Check your SavanaHost welcome email or cPanel sidebar for the exact SSH port and update the `SSH_PORT` GitHub Secret.
- **Firewall IP Blocking**: If SavanaHost restricts SSH connections by IP address, contact SavanaHost support to whitelist GitHub Actions IP ranges or permit key-based authentication.

### Error: `rsync: command not found`
- If SavanaHost's jailed shell does not expose `rsync` in the standard PATH, verify `which rsync` via cPanel terminal. Contact host support to enable rsync in the jailed shell profile.

### Uploaded Images or Data Missing
- The workflow intentionally omits the `--delete` flag. User media uploaded to `buniafrika/assets/uploads/` is never deleted or overwritten during deployment.

