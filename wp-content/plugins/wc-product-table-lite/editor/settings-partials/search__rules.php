<div class="wcpt-search-rules__custom-rules" wcpt-model-key="rules">
  <!-- phrase exact -->
  <div class="wcpt-editor-row-option">
    <label>
      <input type="checkbox" wcpt-model-key="phrase_exact_enabled">
      <span class="wcpt-search-rules__match-name">Exact keyword phrase match</span>
      <input type="number" min="0" wcpt-model-key="phrase_exact_score">
    </label>
  </div>

  <!-- phrase like -->
  <div class="wcpt-editor-row-option">
    <label>
      <input type="checkbox" wcpt-model-key="phrase_like_enabled">
      <span class="wcpt-search-rules__match-name">Keyword phrase matched with wildcard at both ends</span>
      <input type="number" min="0" wcpt-model-key="phrase_like_score">
    </label>
  </div>

  <!-- keyword exact -->
  <div class="wcpt-editor-row-option">
    <label>
      <input type="checkbox" wcpt-model-key="keyword_exact_enabled">
      <span class="wcpt-search-rules__match-name">At least one exact keyword match</span>
      <input type="number" min="0" wcpt-model-key="keyword_exact_score">
    </label>
  </div>

  <!-- keyword like -->
  <div class="wcpt-editor-row-option">
    <label>
      <input type="checkbox" wcpt-model-key="keyword_like_enabled">
      <span class="wcpt-search-rules__match-name">At least one keyword matched with wildcard at both ends</span>
      <input type="number" min="0" wcpt-model-key="keyword_like_score">
    </label>
  </div>

</div>