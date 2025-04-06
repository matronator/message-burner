# Message Burner

![Message Burner logo](https://repository-images.githubusercontent.com/372335603/83432b96-7af6-4cc3-bbd0-db09c26dc09e)

![GitHub issues](https://img.shields.io/github/issues/matronator/message-burner)
![GitHub license](https://img.shields.io/github/license/matronator/message-burner)
![GitHub last commit](https://img.shields.io/github/last-commit/matronator/message-burner)
[![Stand With Ukraine](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/badges/StandWithUkraine.svg)](https://stand-with-ukraine.pp.ua)
[![](https://img.shields.io/github/sponsors/matronator?label=Sponsor&logo=GitHub)](https://github.com/sponsors/matronator)
[![wakatime](https://wakatime.com/badge/user/ed11b7b0-962b-4893-a35b-4539adbcb349/project/d08aed6f-bb27-4b66-8766-d6d641c6b2c1.svg)](https://wakatime.com/badge/user/ed11b7b0-962b-4893-a35b-4539adbcb349/project/d08aed6f-bb27-4b66-8766-d6d641c6b2c1)

[Message Burner](https://burner.matronator.cz) is a web-based application designed for secure and temporary message sharing. It allows users to send messages that self-destruct after being read, ensuring privacy and confidentiality.

**Website**: https://burner.matronator.cz

## Features

- Private message sharing with self-destruction upon reading
- Set a self-destruct timer to delete unopened messages
- Share a text or an image
- Secure PGP encryption of all shared content
- TODO

## Getting Started

### Install Dependencies

Run the following commands in the root folder of the project:

```bash
composer install
npm install # or pnpm, bun, yarn...
```

### Prepare the Data Layer

1. Create a database named `burner`.
2. Run the SQL script located at `init-db.sql` or import it using a database admin tool (e.g., phpMyAdmin, Adminer).
3. Configure the database connection in `app/config/config.local.neon` by providing the database name, login, and password.

## Development

### Front-End Development

Run the following commands to start the front-end development server:

```bash
npm start # starts the dev frontend server
npm run serve # starts backend PHP server
```

The `serve` script will start a server on 127.0.0.1:8000. This is the PHP built-in web server and has debugging panel (Tracy) turned on (if not disabled).

The `start` script will start a server on localhost:3000. Develop on this if you want hot-reloading and automatic browser refresh on changes.

### Admin Module Development

Run the following commands to start the admin module development server:

```bash
npm run start-admin
npm run serve
```

Changes to files in the `dev` folder (except `etc/*`) or templates will automatically refresh the browser.

#### Admin Module Default Credentials

The `init-db.sql` file includes a default admin user:

- **Email:** `info@matronator.com`
- **Password:** `changeme`

> **Important:** Change these credentials before deploying to production.

## Build Process

There are two basic modules - front and admin. Use `dev` and its respective subfolders to create or edit front-end assets. Here is an example of the folder structure:

```
/dev
|-- admin
|   |-- (same structure as front)
`-- front
    |-- images
    |      |-- photo.jpg
    |      `-- chart.png
    |-- icons
    |      |-- mail.svg
    |      `-- arrow.svg
    |-- css
    |   |-- index.js
    |   `-- contact.js
    |-- js
    |   |-- index.css
    |   `-- contact.css
    `-- etc
```

All assets are compiled into `www/dist` folder. For every module subfolder with its name is created.

Keep in mind that files in `images` and `etc` preserve their original directory. Other files (css, js, icons) are generated into the root. For example in `app/components/Hamburger/Hamburger.css` you should reference external images as follows:

```css
.hamburger {
    background-image: url(images/hamburger.svg);
}
```

### Front-End Production Build

Run the following command to create a production build for the front module:

```bash
npm run build
```

### Admin Module Production Build

Run the following command to create a production build for the admin module:

```bash
npm run build-admin
```

## Asset Management

Assets are compiled into the `www/dist` folder.

### Images

To reference images in templates, use the `{asset}` tag with the image path relative to the `www/` folder.

```html
<img src="{asset 'dist/front/images/logo.png'}">
```

### JavaScript and CSS

Similarly, to reference JS and CSS files in templates, use the `|fullpath` filter with the asset filename and it will resolve the path automatically:

```html
<script src="{='index.js'|fullpath}"></script>
<link rel="stylesheet" href="{='main.css'|fullpath}">
```

To add asset from a different module than `front`, add an argument with the module name to the filter. For example, to add assets from `admin` module do this:

```html
<script src="{='index.js'|fullpath,'admin'}"></script>
OR
<link rel="stylesheet" href="{='dashboard.css'|fullpath, module: 'admin'}">
```

## License

This project is licensed under the [MIT License](LICENSE).
