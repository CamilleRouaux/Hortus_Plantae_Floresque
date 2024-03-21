# Instruction d'installation du projet Horus, Plantae, Floresque

Notre projet utilise **PHP 7.4**, une version essentielle pour le bon fonctionnement de Composer.

### Étape 1 :

```bash
composer install
```

### Étape 2 :

Configurez votre connexion à la base de données en créant un fichier '**.env.local**' et ajoutez la ligne suivante :

```makefile
DATABASE_URL="mysql://user:password@127.0.0.1:3306/Hortusss?serverVersion=mariadb-10.3.3&charset=utf8mb4"
```
N'oubliez pas de remplacer "user:password" par le nom d'utilisateur et le mot de passe de votre base de donne, et modifiez la version de MariaDB selon la vôtre.

### Étape 3 :

Crée votre base de données en utilisant le terminal :
```bash
bin/console doctrine:database:create
```

⚠️ **Alternative :** Si vous préférez, vous pouvez aussi créer votre base de donne '**Hortus**' sur adminer directement. Assurez-vous que le user qui a les privilèges sur cette base de données correspondes au '**user:password**' dans le '**.env.local**'.

### Étape 4 :

Ajouter toutes les entités du projet sur la base de donne en migrant toutes les migrations :
```bash
bin/console doctrine:migration:migrate
```

### Étape 5 :

Créé un dossier '**uploads**' et donner lui les droits, afin de pouvoir ajouter des photos sur votre machine : 
 
⚠️ **Attention : Pensé à changer "/chemin/vers/votre/dossier" par le chemin dans lequel vous avez installé le projet**
```bash
sudo mkdir /chemin/vers/votre/dossier/projet-02-hortus/public/uploads
```
⚠️ **Attention : Pensé à changer "user", par le nom d'utilisateur de votre machine dans lequel vous avez installé le projet**
```bash
sudo chown user:www-data /chemin/vers/votre/dossier/projet-02-hortus/public/uploads
```
```bash
sudo chmod 775 /chemin/vers/votre/dossier/projet-02-hortus/public/uploads
```
### Étape 6 :

⚠️ **Attention : à modifier uniquement si les liens du projet vous retournent une page apache 404**  
Pour garantir le bon fonctionnement d'un projet Symfony, veillez à configurer Apache2 de manière à autoriser l'accès à tous les utilisateurs possédant les droits nécessaires pour accéder aux ressources de ce projet:

```apache2.conf
<Directory votre/chemin/vers/le/projet/projet-02-hortus>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

Ajuster : 'votre/chemin/vers/le/projet' par le chemin dans lequel vous avez cloné le repo.

### Étape 7 :

Pour tester le site avec toutes les connexions utilisateurs et les permissions, veuillez utiliser data fixture qui crée trois utilisateurs : 'admin', 'redactor', 'member':

```bash
bin/console doctrine:fixtures:load
```
### Étape 8 :

Profiter de notre projet Hortus en naviguant, non-connecter ou connecter avec les utilisateurs :

admin:    admin@hotus.com  
password: Hortus  
  
admin:    redactor@hotus.com  
password: Hortus  
  
admin:    member@hotus.com  
password: Hortus  