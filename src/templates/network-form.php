<main class="content">
        <div class="page-header">
            <h1>Configuration Réseau</h1>
        </div>

        <p>Paramètres IP de votre box</p>
        <div class="2-card">
        <div class="card">
            <form id="networkForm" method="POST">
                <!-- Adresse IP -->
                <div class="form-group">
                    <label class="form-label">Adresse IP de la Box</label>
                    <div class="ip-input-group">
                        <input type="number" class="ip-byte" name="ip1" min="0" max="255" value="192" readonly>
                        <span class="separator">.</span>
                        <input type="number" class="ip-byte" name="ip2" min="0" max="255" value="168" readonly>
                        <span class="separator">.</span>
                        <input type="number" class="ip-byte" name="ip3" min="0" max="255" value="<?php echo $ip3; ?>" readonly>
                        <span class="separator">.</span>
                        <input type="number" class="ip-byte" name="ip4" min="0" max="255" value="<?php echo $ip4; ?>" required>
                    </div>
                </div>

                <!-- Masque de sous-réseau -->
                <div class="form-group">
                    <label class="form-label">Masque de Sous-Réseau</label>
                    <div class="ip-input-group">
                        <input type="number" class="ip-byte" name="mask1" min="0" max="255" value="255" readonly>
                        <span class="separator">.</span>
                        <input type="number" class="ip-byte" name="mask2" min="0" max="255" value="255" readonly>
                        <span class="separator">.</span>
                        <input type="number" class="ip-byte" name="mask3" min="0" max="255" value="<?php echo $mask3; ?>" readonly>
                        <span class="separator">.</span>
                        <input type="number" class="ip-byte" name="mask4" min="0" max="255" value="<?php echo $mask4; ?>" readonly>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="button-group">
                    <button type="reset" class="btn btn-secondary">Annuler</button>
                    <button type="submit" class="btn btn-primary" name="submit_ip">Enregistrer</button>
                </div>
            </form>
        </div>

        
        <div class="card">
            <p>IP: 192.168.1.1</p>
        </div>
        </div>

        <br>
        <p>Paramètres DHCP de votre box</p>
        <div class="card">
            <form id="networkForm" method="POST">
                <!-- Adresse IP -->
                <div class="form-group">
                    <label class="form-label">Nombre de machine sur le réseau</label>
                    <div class="ip-input-group">
                        <input type="number" class="ip-byte" name="dhcp_client" min="1" max="65536" value="1" required>
                    </div>
                </div>
                <!-- Boutons d'action -->
                <div class="button-group">
                    <button type="reset" class="btn btn-secondary">Annuler</button>
                    <button type="submit" class="btn btn-primary" name="submit_dhcp">Enregistrer</button>
                </div>
            </form>
        </div>

    </main>
