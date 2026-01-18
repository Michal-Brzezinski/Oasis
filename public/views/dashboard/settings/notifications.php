<h2>Powiadomienia</h2>

<form method="POST" class="form-card">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

    <div class="form-group">
        <label>
            <input type="checkbox" name="email_alerts">
            Powiadomienia email
        </label>
    </div>

    <div class="form-group">
        <label>
            <input type="checkbox" name="sms_alerts">
            Powiadomienia SMS
        </label>
    </div>

    <div class="form-group">
        <label>
            <input type="checkbox" name="watering_alerts">
            Alerty o podlewaniu
        </label>
    </div>

    <button class="btn-primary" type="submit">Zapisz</button>
</form>