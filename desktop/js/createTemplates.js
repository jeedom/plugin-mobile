function createMultiStateTemplate() {
    return `
        <div class="containerMultiState">
      <div class="toggle_radio">
        <input type="radio" class="toggle_option" id="first_toggle" name="toggle_option">
        <input type="radio" class="toggle_option" id="second_toggle" name="toggle_option" checked>
        <input type="radio" class="toggle_option" id="third_toggle" name="toggle_option">
        <label for="first_toggle"><p>Optionun</p></label>
        <label for="second_toggle"><p>OptionDeux</p></label>
        <label for="third_toggle"><p>OptionTrois</p></label>
        <div class="toggle_option_slider"></div>
      </div>
    </div>
    `;
  }
  
  function createSwitchemplate() {
    return `
        <span class="toggle-switch">
           <span class="toggle-knob"></span>
        </span>
    `;
  }
  
  
  function ignoreClickOnTemplateTile(event) {
    if (event.target.closest('.toggle_radio')) {
      return true; 
    }
    if (event.target.closest('.toggle-switch')) {
      return true; 
    }
  }