{strip}
  <div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{t}確認{/t}</h4>
          <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
        </div>
        <div class="modal-body">
            {t}確認します{/t}
        </div>
        <div class="modal-footer">
          <form method="post" action="" id="confirmModal_jump_to">
            <input type="hidden" id="confirmModal_url" value="">
            <input type="hidden" id="confirmModal_form_id" value="">
              {if $data.token_value != ""}<input type="hidden" name="{$data.token_name}" value="{$data.token_value}">{/if}
            <button type="button" class="btn btn-{$this->priority_level->danger} button-yes" onclick="exec_yes()">{t}はい{/t}</button></form>
          <button type="button" class="btn btn-{$this->priority_level->primary} button-no" data-dismiss="modal">{t}いいえ{/t}</button>
        </div>
      </div>
    </div>
  </div>
  <script>
      {literal}
      $(function () {
        $("#confirmModal").on("show.bs.modal", function(e) {
          let button = $(e.relatedTarget);
          $(".modal-title").html(button.data('title'));
          $(".modal-body").html(button.data('body'));
          $(".button-yes").html(button.data('button-yes'));
          $(".button-no").html(button.data('button-no'));
          let url = button.data('href');
          let form_id = button.data('form-id');
          if( url !== undefined ){
            $("#confirmModal_jump_to").attr("action",url);
            $("#confirmModal_url").val(url)
          }else if( form_id !== undefined ){
            $("#confirmModal_form_id").val(form_id)
          }
        });
      });

      function exec_yes() {
        if( $("#confirmModal_url").val() !== "" ){
          $("#confirmModal_jump_to").submit();
        }else if( $("#confirmModal_form_id").val() !== "" ){
          let form_id = $("#confirmModal_form_id").val();
          $("#"+form_id).submit();
        }
        return false;
      }

      {/literal}
  </script>
{/strip}