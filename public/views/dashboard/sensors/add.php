<div class="sensors-add-view">
    <form action="/dashboard/sensors/add" method="POST" class="form-card">
        <div class="form-group">
            <label for="name">Nazwa czujnika</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="type">Typ czujnika</label>
            <select id="type" name="type" required>
                <option value="temperature">Temperatura</option>
                <option value="humidity">Wilgotność</option>
                <option value="soil">Wilgotność gleby</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Dodaj czujnik</button>
            <a href="/dashboard/sensors" class="btn-secondary">Anuluj</a>
        </div>
    </form>
</div>