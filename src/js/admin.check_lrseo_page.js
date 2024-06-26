export const checkLrseoAdminPage = () => {
    // On vérifie par rapport a l'URL, il faut qu'il y est page=lrseo
    return window.location.href.indexOf('page=lrseo') !== -1;
}
