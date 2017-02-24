<p align="center">
  <img src="https://cloud.githubusercontent.com/assets/7980426/19796553/213e8998-9c9a-11e6-81aa-2b11b5ff25db.png"><br />
  An ecomerce platform for October CMS.
</p>
<p align="center">
  <a href="https://travis-ci.org/scottbedard/oc-shop-plugin"><img src="https://travis-ci.org/scottbedard/oc-shop-plugin.svg?branch=master" alt="Build Status"></a>
  <a href="https://coveralls.io/github/scottbedard/oc-shop-plugin?branch=master"><img src="https://coveralls.io/repos/github/scottbedard/oc-shop-plugin/badge.svg?branch=master" alt="Coverage Status"></a>
  <a href="https://scrutinizer-ci.com/g/scottbedard/oc-shop-plugin/"><img src="https://img.shields.io/scrutinizer/g/scottbedard/oc-shop-plugin.svg" alt="Scrutinizer"></a>
  <a href="https://styleci.io/repos/47805210"><img src="https://styleci.io/repos/47805210/shield?style=flat" alt="Style CI"></a>
  <a href="https://github.com/scottbedard/oc-shop-plugin/blob/master/LICENSE"><img src="https://img.shields.io/badge/license-MIT-blue.svg" alt="License"></a>
</p>

---

<a name="about"></a>
### About this project

> **Warning:** This repository is a work in progress, and is not ready for use by anyone.

This repository has undergone a number of complete rewrites. It is my hope that this version will be the one to finally make it to the October marketplace as a free plugin. To achieve this, I am scaling back the scope and complexity of this plugin with one goal in mind; to create a core set of reliable and well tested features. Once this is done, I will consider re-implementing some of the more advanced features that other versions had.

To see the progress being made toward the first release, check out the [first release milestone](https://github.com/scottbedard/oc-shop-plugin/milestone/1).

<a name="local-development"></a>
### Local development

To properly configure the dev environment, create an `.october_proxy` file in `/plugins/bedard/shop` that contains the application URL of your October site.

```bash
# start the development server and watch
$ yarn run dev

# build production assets
$ yarn run build
```
