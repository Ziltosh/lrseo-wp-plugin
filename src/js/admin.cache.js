/**
 * Stores an item in the local storage with an expiration time.
 *
 * @param {any} item - The item to be stored.
 * @param {string} name - The name of the item.
 * @param {number} [expiration=1] - The expiration time in minutes.
 */
function storeItem(item, name, expiration = 1) {
    // On enregistre dans le local storage en gardant ajoutant une date d'expiration a expiration minute(s)
    let date = new Date();
    date.setMinutes(date.getMinutes() + expiration);
    localStorage.setItem(name, JSON.stringify({item, expiration: date}));
    // On enregistre la clé dans un tableau pour pouvoir la supprimer plus tard
    let items = localStorage.getItem('items');
    if (items) {
        items = JSON.parse(items);
    } else {
        items = [];
    }
    items.push(name);
    localStorage.setItem('items', JSON.stringify(items));
}

/**
 * Retrieves an item from the local storage.
 *
 * @param {string} name - The name of the item.
 * @returns {any|null} The retrieved item or null if the item does not exist or is expired.
 */
function getItem(name) {
    // On récupère l'item dans le local storage
    let item = localStorage.getItem(name);
    if (item) {
        item = JSON.parse(item);
        // On vérifie si l'item est encore valide
        if (new Date(item.expiration) > new Date()) {
            return item.item;
        } else {
            // On supprime l'item si il n'est plus valide
            deleteItem(name);
        }
    }
    return null;
}

/**
 * Deletes an item from the local storage.
 *
 * @param {string} name - The name of the item.
 */
function deleteItem() {
    // On supprime l'item du local storage
    localStorage.removeItem(name);
    // On supprime la clé du tableau
    let items = localStorage.getItem('items');
    if (items) {
        items = JSON.parse(items);
        items = items.filter(item => item !== name);
        localStorage.setItem('items', JSON.stringify(items));
    }
}

/**
 * Clears all expired items from the local storage.
 */
function clearExpiredItems() {
    // On récupère les items dans le local storage
    let items = localStorage.getItem('items');
    if (items) {
        items = JSON.parse(items);
        // On filtre les items qui sont encore valides
        items = items.filter(item => new Date(item.expiration) > new Date());
        // On les remet dans le local storage
        localStorage.setItem('items', JSON.stringify(items));
    }
    // On supprime les items qui ne sont plus valides
    items.forEach(item => {
        deleteItem(item);
    });

}

/**
 * An object that exposes methods for interacting with the local storage.
 *
 * @typedef {Object} Store
 * @property {function(any, string, number): void} store - Stores an item in the local storage.
 * @property {function(string): any|null} get - Retrieves an item from the local storage.
 * @property {function(string): void} delete - Deletes an item from the local storage.
 * @property {function(): void} clear - Clears all expired items from the local storage.
 */
export const Store = {
    store: storeItem,
    get: getItem,
    delete: deleteItem,
    clear: clearExpiredItems,
}