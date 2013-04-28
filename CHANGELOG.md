CHANGELOG
=========

## 2.1.3

 * fa1f544 ImageController: We should not add "directory separator" twice on "folder"
 * d9df90c Load resource file only if required
 * a3ea244 Fix empty array js issue when using genemu_jquerygeolocation with no option

## 2.1.2

 * 87e517e [Captcha] DI - fixed bug when assets are not installed yet
 * 835ffa2 [Recaptcha] added support for a proxy forwarding the HTTP request
 * 7a9406c [Captcha] Don't fail at compile-time when GD isn't available
 * c75972b [Recaptcha] allowed to configure validation options (fixes #233)

## 2.1.1

 * b03eb10 fixed recaptcha init bug
 * 2b9f4e2 [Recaptcha] switched api URL to google domain for client side part
 * b48e37e [ReCaptcha] added a way for automated testing (issue #215)