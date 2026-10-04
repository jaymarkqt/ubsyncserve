window.tableStateApi = {
    inFlightLoad: null,
    async load() {
        if (this.inFlightLoad) {
            return this.inFlightLoad;
        }

        this.inFlightLoad = this.fetchTables();
        try {
            return await this.inFlightLoad;
        } finally {
            this.inFlightLoad = null;
        }
    },

    async fetchTables() {
        const response = await fetch('/api/tables', {
            headers: { 'Accept': 'application/json' }
        });

        if (!response.ok) {
            throw new Error('Unable to load table statuses from the server.');
        }

        return response.json();
    },

    async save(tables) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const response = await fetch('/api/tables', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ tables })
        });

        if (!response.ok) {
            const result = await response.json().catch(() => ({}));
            const error = new Error(result.message || 'Unable to save table statuses to the server.');
            alert(error.message);
            throw error;
        }
    },

    async clear(tableNumber) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const response = await fetch(`/api/tables/${encodeURIComponent(tableNumber)}/clear`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken || '',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            const result = await response.json().catch(() => ({}));
            throw new Error(result.message || 'Unable to clear the table.');
        }
    }
};
