<p align="center"><h1>oc-shop-plugin<h1></p>
<p align="center"><h3>An ecommerce platform for October CMS.<h3></p>
<p align="center"> 
  <a href="https://travis-ci.org/scottbedard/oc-shop-plugin"><img src="https://travis-ci.org/scottbedard/oc-shop-plugin.svg?branch=master" alt="Build Status"></a>
  <a href="https://coveralls.io/github/scottbedard/oc-shop-plugin?branch=master"><img src="https://coveralls.io/repos/github/scottbedard/oc-shop-plugin/badge.svg?branch=master" alt="Coverage Status"></a>
  <a href="https://styleci.io/repos/47805210"><img src="https://styleci.io/repos/47805210/shield?style=flat" alt="Style CI"></a>
  <a href="https://github.com/scottbedard/oc-shop-plugin/blob/master/LICENSE"><img src="https://img.shields.io/badge/license-MIT-blue.svg" alt="License"></a>
</p>

---

<a name="about"></a>
### About this project

> **Warning:** This repository is a work in progress, and is not ready for use by anyone.

This repository has undergone a number of complete rewrites. It is my hope that this version will be the one to finally make it to the October marketplace as a free plugin. To achieve this, I am scaling back the scope and complexity of this plugin with one goal in mind; to create a core set of reliable and well tested features. Once this is done, I will consider re-implementing some of the more advanced features that other versions had.

To see the progress being made toward the first release, check out the [1.0 milestone](https://github.com/scottbedard/oc-shop-plugin/milestone/1).

<a name="local-development"></a>
### Local development

To properly configure the dev environment, create an `.october_proxy` file in `/plugins/bedard/shop` that contains the URL of your local October site. This enables the Webpack dev server to proxy requests to your site while continuing to serve hot-reloadable assets.

To fire up the dev server, run the following command.

```bash
$ npm run dev
```

Production assets can be built with the following command.

```bash
$ npm run build
```
