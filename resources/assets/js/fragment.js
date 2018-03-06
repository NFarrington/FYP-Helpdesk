
/**
 * Remove hash fragments when redirected from OAuth.
 */
if (window.location.hash.match(/^(#_=_)?$/)) {
    history.pushState('', document.title, window.location.pathname + window.location.search);
}
