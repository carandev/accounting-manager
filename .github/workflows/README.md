# CI/CD Pipeline - Configuración Self-Hosted Runner

Este documento describe cómo configurar y usar el pipeline de CI/CD para el proyecto Accounting Manager.

## Características

- ✅ Build automático del Dockerfile
- ✅ Push de imagen a GitHub Container Registry (ghcr.io)
- ✅ Despliegue automático en servidor self-hosted
- ✅ Persistencia de datos (volúmenes de MySQL y uploads)
- ✅ Ejecución de migraciones automáticas
- ✅ Limpieza automática de imágenes antiguas

## Requisitos Previos

### En el servidor (Self-Hosted Runner)

1. **Docker y Docker Compose** instalados
2. **GitHub Actions Runner** configurado
3. Acceso a GitHub Container Registry

## Configuración del Self-Hosted Runner

### 1. Instalar GitHub Actions Runner

En tu servidor, ejecuta:

```bash
# Crear directorio para el runner
mkdir actions-runner && cd actions-runner

# Descargar el runner (Linux x64)
curl -o actions-runner-linux-x64-2.311.0.tar.gz -L https://github.com/actions/runner/releases/download/v2.311.0/actions-runner-linux-x64-2.311.0.tar.gz

# Extraer el instalador
tar xzf ./actions-runner-linux-x64-2.311.0.tar.gz

# Configurar el runner
./config.sh --url https://github.com/carandev/accounting-manager --token YOUR_TOKEN

# Instalar como servicio
sudo ./svc.sh install
sudo ./svc.sh start
```

**Nota:** Obtén el token de configuración desde:
`https://github.com/carandev/accounting-manager/settings/actions/runners/new`

### 2. Instalar Docker (si no está instalado)

```bash
# Ubuntu/Debian
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Añadir usuario al grupo docker
sudo usermod -aG docker $USER

# Instalar Docker Compose
sudo apt-get update
sudo apt-get install docker-compose-plugin
```

### 3. Configurar Permisos

```bash
# Asegurar que el runner tenga permisos para Docker
sudo usermod -aG docker $(whoami)

# Reiniciar el servicio del runner
sudo ./svc.sh stop
sudo ./svc.sh start
```

## Estructura del Pipeline

### Job 1: Build and Push
- Construye la imagen Docker desde el Dockerfile
- Sube la imagen a GitHub Container Registry
- Usa cache para acelerar builds subsecuentes
- Genera tags automáticos basados en branch/commit

### Job 2: Deploy
- Solo se ejecuta en branches `main` y `develop`
- Crea directorios de persistencia si no existen
- Actualiza docker-compose.yaml con la nueva imagen
- Hace pull de las imágenes
- Detiene contenedores antiguos (sin eliminar volúmenes)
- Inicia nuevos contenedores
- Ejecuta migraciones de base de datos
- Limpia cache de Laravel
- Verifica que los contenedores estén corriendo

### Job 3: Notify
- Notifica el resultado del despliegue

## Persistencia de Datos

El pipeline mantiene la persistencia mediante volúmenes Docker:

```yaml
volumes:
  - ./noton-data/uploads:/srv/www/storage/app/public  # Archivos subidos
  - ./mysql-data:/var/lib/mysql                        # Base de datos
```

Estos directorios **NO se eliminan** durante el despliegue, asegurando que no se pierdan datos.

## Workflow de Despliegue

### Despliegue Automático

El pipeline se ejecuta automáticamente cuando:
- Se hace push a `main` o `develop`
- Se crea un Pull Request hacia `main` o `develop`

### Despliegue Manual

También puedes ejecutar el pipeline manualmente:
1. Ve a: `https://github.com/carandev/accounting-manager/actions`
2. Selecciona "CI/CD Pipeline"
3. Click en "Run workflow"

## Variables de Entorno

El pipeline usa las siguientes variables automáticas:
- `GITHUB_TOKEN`: Token automático para autenticación
- `github.repository`: Nombre del repositorio
- `github.actor`: Usuario que ejecuta la acción

## Troubleshooting

### El runner no se conecta
```bash
# Verificar estado del servicio
sudo ./svc.sh status

# Ver logs
journalctl -u actions.runner.*
```

### Error de permisos de Docker
```bash
# Verificar que el usuario está en el grupo docker
groups

# Si no está, añadirlo y reiniciar sesión
sudo usermod -aG docker $USER
```

### Contenedores no inician
```bash
# Ver logs de los contenedores
docker compose logs -f

# Verificar estado
docker compose ps
```

### Error de espacio en disco
```bash
# Limpiar imágenes antiguas manualmente
docker system prune -a --volumes

# Verificar espacio
df -h
```

## Seguridad

- El `GITHUB_TOKEN` se genera automáticamente y expira después de cada job
- Las credenciales de base de datos deben configurarse en el servidor
- Los volúmenes de datos tienen permisos restrictivos

## Próximos Pasos

1. Configurar el self-hosted runner en tu servidor
2. Hacer push de esta rama a GitHub
3. Crear un Pull Request para revisar los cambios
4. Merger a `main` para activar el despliegue automático

## Soporte

Para más información sobre self-hosted runners:
- [Documentación oficial](https://docs.github.com/en/actions/hosting-your-own-runners)
- [Troubleshooting](https://docs.github.com/en/actions/hosting-your-own-runners/managing-self-hosted-runners/monitoring-and-troubleshooting-self-hosted-runners)
