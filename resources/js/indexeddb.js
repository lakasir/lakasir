// public/js/indexedDB.js

class IndexedDBManager {
  constructor() {
    this.dbName = 'laravelDB';
    this.dbVersion = 1;
    this.db = null;
  }

  async initDB() {
    return new Promise((resolve, reject) => {
      const request = indexedDB.open(this.dbName, this.dbVersion);

      request.onerror = (event) => {
        reject('IndexedDB error: ' + event.target.error);
      };

      request.onsuccess = (event) => {
        this.db = event.target.result;
        resolve(this.db);
      };

      request.onupgradeneeded = (event) => {
        const db = event.target.result;

        // Create object stores as needed
        if (!db.objectStoreNames.contains('tasks')) {
          const taskStore = db.createObjectStore('tasks', { keyPath: 'id', autoIncrement: true });
          taskStore.createIndex('status', 'status');
          taskStore.createIndex('created_at', 'created_at');
        }
      };
    });
  }

  async addItem(storeName, item) {
    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction([storeName], 'readwrite');
      const store = transaction.objectStore(storeName);

      const request = store.add({
        ...item,
        created_at: new Date().toISOString(),
        synced: false
      });

      request.onsuccess = () => resolve(request.result);
      request.onerror = () => reject(request.error);
    });
  }

  async getItems(storeName) {
    return new Promise((resolve, reject) => {
      const transaction = this.db.transaction([storeName], 'readonly');
      const store = transaction.objectStore(storeName);
      const request = store.getAll();

      request.onsuccess = () => resolve(request.result);
      request.onerror = () => reject(request.error);
    });
  }

  async syncWithServer(storeName) {
    const items = await this.getItems(storeName);
    const unsynced = items.filter(item => !item.synced);

    if (unsynced.length === 0) return;

    try {
      const response = await fetch('/api/sync', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ items: unsynced })
      });

      if (!response.ok) throw new Error('Sync failed');

      // Mark items as synced
      const transaction = this.db.transaction([storeName], 'readwrite');
      const store = transaction.objectStore(storeName);

      for (const item of unsynced) {
        item.synced = true;
        store.put(item);
      }
    } catch (error) {
      console.error('Sync error:', error);
      throw error;
    }
  }
}
