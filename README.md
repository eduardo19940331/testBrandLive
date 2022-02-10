Estos archivos Docker le permiten ejecutar un proyecto PHP Symfony 3.4 Framework.



## Comentarios
Estos archivos Docker le permiten ejecutar un proyecto PHP Symfony Framework, además en esta aplicación tenemos incorporado el uso de Make Command
para utilizarla necesitamos instalar la aplicación.

## Instalación de Make

Actualizamos las dependencias.
```bash
sudo apt-get update.
```

Luego, para instalar:
```bash
sudo apt-get install ubuntu-make
```

Para ver la lista de comandos para utilizar en make vea el archivo de comandos de Makefile dentro de este proyecto.

## Uso Aplicación

Vamos al repositorio y copiamos el archivo .env.example y reemplazamos el nombre por .env (a secas)

Puede ejecutar el contenedor mediante este comando:

Primera vez (para construir los contenedores en docker)
```bash
docker-compose build
```

Puede ejecutar el contenedor mediante este comando:
```bash
docker-compose up 
```
Puedes agregar -d al final para que la ejecución se realice en segundo plano, es decir, no muestre detalles de la ejecución.

Una vez correindo los contenedores de docker podemos entrar en el contenedor de php para ejecutar comandos php y composer
(necesario para generar el directorio vendor)
Como tenemos make instalado utilizamos el siguiente comando
```bash
make ssh-php
```

Ingresamos al contenedor y mostrará una nueva línea de comandos, escribimos:
```bash
composer install
```

## Ajustar los permisos de volumen de Docker
Si está utilizando Docker Engine en una máquina virtual Linux, debe poner los siguientes permisos en el volumen de Docker

```bash
chown -R [user]:www-data volumes/project
chmod 775 -R volumes/project
```

Si agrega nuevos archivos, recuerde corregir los permisos nuevamente.

Esto es necesario para modificar los archivos de volumen fuera del contenedor de Docker.

## Utilice el contenedor MySQL.
Este repositorio incluye una imagen Docker de MySQL. Si el contenedor se está ejecutando, puede acceder a través de cualquier cliente MySQL, por ejemplo, Squirrel SQL o MySQL Workbench a través de 127.0.0.1 ip y puerto 3306.

La comunicación entre contenedores es a través del alias "mysql", el nombre del servicio en docker-compose.yaml

## License
[MIT](https://choosealicense.com/licenses/mit/)
