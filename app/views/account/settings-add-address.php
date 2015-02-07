<fieldset class="form-account-settings-address-new">
  <legend>New Address</legend>
  <input type="text" name="addressUnit" placeholder="Unit (optional)" class="form-input-block" />
  <div class="form-input-thirdblock-container">
    <div class="form-input-thirdblock">
      <input type="text" name="addressNumber" placeholder="Street Number" class="form-input-block" />
    </div>
    <div class="form-input-thirdblock">
      <input type="text" name="addressName" placeholder="Street Name" class="form-input-block" />
    </div>
    <div class="form-input-thirdblock">
      <input type="text" name="addressType" placeholder="Street Type" class="form-input-block" />
    </div>
  </div>
  <div class="form-input-thirdblock-container">
    <div class="form-input-thirdblock">
      <input type="text" name="addressCity" placeholder="Suburb" class="form-input-block" />
    </div>
    <div class="form-input-thirdblock">
      <select name="addressState" placeholder="State" class="form-input-block">
        <option value="NSW">NSW</option>
        <option value="VIC">VIC</option>
      </select>
    </div>
    <div class="form-input-thirdblock">
      <input type="text" name="addressPostcode" placeholder="Postcode" class="form-input-block" />
    </div>
  </div>
  <input id="accountSettingsConfirmAddAddress" type="submit" name="confirmAddAddress" value="Add Address" class="form-button form-input-block" />
</fieldset>
