Symfony Prueba tecnica Fidelizador
========================
Esta guía proporciona los pasos necesarios para instalar y configurar la aplicación Symfony Prueba técnica Fidelizador. Asegúrese de seguir las instrucciones cuidadosamente para garantizar una instalación exitosa.

# Clonar el repositorio.
--------------
Descargar el proyecto utilizando el comando git clone o descargando el archivo ZIP desde el repositorio en GitHub: https://github.com/brunosilva9/testFide.
Abrir una terminal y navegar hasta el directorio raíz del proyecto descargado.

git clone https://github.com/brunosilva9/testFide.git
cd testFide/

# Iniciar.
--------------
Descargaremos las dependencias del proyecto con lo cual se creara el directorio 'vendor'  con el comando:

    composer install

* Si es la primera vez que se ejecuta en el directorio pidira unas configuraciones:
En parentesis es el valor default, que se autocompleta si presionas Enter.
* * Configuracion sugerida:
    database_host (127.0.0.1):
    database_port (null): 3306
    database_name (symfony): auth-fide
    database_user (root):
    database_password (null): admin
    mailer_transport (smtp):
    mailer_host (127.0.0.1):
    mailer_user (null):
    mailer_password (null):
    secret (ThisTokenIsNotSoSecretChangeIt):

En caso  de necesitar hacer un cambio puede dirigirte al archivo  paramemeters.yml em app/config/

# Crear la base de datos.

    php bin/console doctrine:database:create
    php bin/console doctrine:schema:update --dump-sql
    php bin/console doctrine:schema:update --force


# Poblar la base de datos.
    Puedes usar la misma aplicacion para crear un usario valido. 
    En caso contrario usar este insert, pero deberas encododear la contraseña con bcrypt.

    INSERT INTO user (username, password, is_admin) VALUES ('admin', 'helloworld', 0);
    * recuerda la contraseña debe estar codificada.

    ya que tiene al usario creado puede hacer algunos insert para que la app funcione
    en la tabla pet y owner:

    INSERT INTO owner (rut, name, lastName) VALUES ('12345678-5', 'Juan', 'Perez');
    INSERT INTO owner (rut, name, lastName) VALUES ('98765432-1', 'Maria', 'Gomez');
    INSERT INTO owner (rut, name, lastName) VALUES ('65432198-7', 'Pedro', 'Lopez');
    INSERT INTO owner (rut, name, lastName) VALUES ('18708323-0', 'Bruno', 'Silva');

    INSERT INTO pet (chipNumber, type, name, lastName, sex, color, dateOfBirth, neutered, humanRut, observations)
    VALUES (123456789, 'Gato', 'Auron', 'Paredes', 1, 'Gris', '2021-10-06', 1, '18708323-0', '');
    INSERT INTO pet (chipNumber, type, name, lastName, sex, color, dateOfBirth, neutered, humanRut, observations)
    VALUES (987654321, 'Gato', 'Auron', 'Paredes', 1, 'Gris', '2021-10-06', 1, '18708323-0', '');
    INSERT INTO pet (chipNumber, type, name, lastName, sex, color, dateOfBirth, neutered, humanRut, observations)
    VALUES (654321987, 'Perro', 'Oso', 'Paredes', 1, 'Crema', '2018-09-09', 0, '18708323-0', '');


# Para iniciar el proyecto.

php bin/console server:run
Acceda a la aplicación en su navegador web utilizando la siguiente URL: http://localhost:8000.
* Puede cambiar el puerto si ya estas ocupando el 8000.

