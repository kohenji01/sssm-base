{strip}
{locale path="../Language/Locale" domain=$CI->sssm->getTextDomain loc=$smarty.session[$CI->sssm->systemName].locale}
<!DOCTYPE html>
<html lang="ja">
{include file="./head.tpl"}
<body id="body" class="text-center">
{if $CI->sssm->include_file.pre_header}
    {include file=$CI->sssm->include_file.pre_header}
{/if}
{if $CI->sssm->include_file.header}
    {include file=$CI->sssm->include_file.header}:
{/if}
{if $CI->sssm->include_file.body}
    {include file=$CI->sssm->include_file.body}
{/if}
{if $CI->sssm->include_file.footer}
    {include file=$CI->sssm->include_file.footer}
{/if}
{if $CI->sssm->include_file.suf_footer}
    {include file=$this->include_file.suf_footer}
{/if}
</body>
</html>
{/strip}