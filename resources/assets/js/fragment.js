
/**
 * Remove hash fragments when redirected from OAuth.
 */
if (window.location.href.indexOf('#') !== -1 && window.location.hash.match(/^$|^#$|^#_=_$/)) {
    history.pushState('', document.title, window.location.pathname + window.location.search);
}
