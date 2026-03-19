#!/usr/bin/env bash
set -euo pipefail

APP_NAME="audit-management-system"
APP_VERSION="1.0.0"
JAR_NAME="${APP_NAME}-${APP_VERSION}.jar"
TARGET_JAR="target/${JAR_NAME}"
INSTALL_DIR="/opt/audit-management"
SERVICE_NAME="audit-management"
SERVICE_FILE="deployment/rhel8/${SERVICE_NAME}.service"
ENV_EXAMPLE="deployment/rhel8/${SERVICE_NAME}.env.example"
ENV_FILE="/etc/audit-management/${SERVICE_NAME}.env"
APP_USER="auditapp"
APP_GROUP="auditapp"
APP_JAR_PATH="${INSTALL_DIR}/${APP_NAME}.jar"

log() {
  printf '%s\n' "$1"
}

require_command() {
  if ! command -v "$1" >/dev/null 2>&1; then
    log "Error: required command '$1' is not installed or not in PATH."
    exit 1
  fi
}

log "Starting Audit Management System deployment..."

require_command java
require_command mvn

if [[ ! -f pom.xml ]]; then
  log "Error: pom.xml not found. Run this script from the project root."
  exit 1
fi

log "Building Spring Boot application with Maven..."
mvn clean package -DskipTests

if [[ ! -f "${TARGET_JAR}" ]]; then
  log "Error: expected artifact '${TARGET_JAR}' was not generated."
  exit 1
fi

log "Preparing Linux service account and directories..."
sudo groupadd -f "${APP_GROUP}"
id -u "${APP_USER}" >/dev/null 2>&1 || sudo useradd -r -g "${APP_GROUP}" -d "${INSTALL_DIR}" -s /sbin/nologin "${APP_USER}"
sudo mkdir -p "${INSTALL_DIR}" /etc/audit-management /var/log/audit-management
sudo chown -R "${APP_USER}:${APP_GROUP}" "${INSTALL_DIR}" /var/log/audit-management
sudo chmod 750 "${INSTALL_DIR}" /var/log/audit-management

log "Installing application artifact..."
sudo cp "${TARGET_JAR}" "${APP_JAR_PATH}"
sudo chown "${APP_USER}:${APP_GROUP}" "${APP_JAR_PATH}"

if [[ -f "${SERVICE_FILE}" ]]; then
  log "Installing systemd unit..."
  sudo cp "${SERVICE_FILE}" "/etc/systemd/system/${SERVICE_NAME}.service"
  if [[ ! -f "${ENV_FILE}" && -f "${ENV_EXAMPLE}" ]]; then
    sudo cp "${ENV_EXAMPLE}" "${ENV_FILE}"
  fi
  sudo systemctl daemon-reload
  sudo systemctl enable "${SERVICE_NAME}"
  sudo systemctl restart "${SERVICE_NAME}"
  log "Deployment completed successfully."
  log "Service status: sudo systemctl status ${SERVICE_NAME}"
else
  log "Deployment completed successfully."
  log "Note: systemd unit file '${SERVICE_FILE}' was not found, so no service was installed."
fi
