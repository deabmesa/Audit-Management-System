#!/usr/bin/env bash
set -euo pipefail

APP_USER="auditapp"
APP_GROUP="auditapp"
APP_HOME="/opt/audit-management"
APP_ETC="/etc/audit-management"
APP_LOG_DIR="/var/log/audit-management"

assert_supported_os() {
  local release_file="/etc/redhat-release"
  if [[ ! -f "${release_file}" ]]; then
    echo "Warning: ${release_file} not found. Continuing without OS validation."
    return
  fi

  local release_text
  release_text=$(<"${release_file}")
  case "${release_text}" in
    *"release 8."*)
      echo "Detected supported Red Hat Enterprise Linux 8 family host: ${release_text}"
      ;;
    *)
      echo "Error: this installer targets Red Hat Enterprise Linux 8.x (tested target: 8.10)."
      echo "Detected host: ${release_text}"
      exit 1
      ;;
  esac
}


assert_supported_os

sudo groupadd -f "${APP_GROUP}"
id -u "${APP_USER}" >/dev/null 2>&1 || sudo useradd -r -g "${APP_GROUP}" -d "${APP_HOME}" -s /sbin/nologin "${APP_USER}"

sudo mkdir -p "${APP_HOME}" "${APP_ETC}" "${APP_LOG_DIR}"
sudo chown -R "${APP_USER}:${APP_GROUP}" "${APP_HOME}" "${APP_LOG_DIR}"
sudo chmod 750 "${APP_HOME}" "${APP_LOG_DIR}"

sudo cp deployment/rhel8/audit-management.service /etc/systemd/system/audit-management.service
if [[ ! -f /etc/audit-management/audit-management.env ]]; then
  sudo cp deployment/rhel8/audit-management.env.example /etc/audit-management/audit-management.env
fi

sudo systemctl daemon-reload
sudo systemctl enable audit-management

echo "Service installed."
echo "1) Copy jar to ${APP_HOME}/audit-management-system.jar"
echo "2) Update /etc/audit-management/audit-management.env"
echo "3) Start: sudo systemctl start audit-management"
