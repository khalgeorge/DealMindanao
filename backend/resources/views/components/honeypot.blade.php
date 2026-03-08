{{--
    Honeypot + timing component.
    Include inside every public <form> right after @csrf.

    - Sets a session timestamp so the server can reject instant (bot) submissions.
    - Renders two invisible fields; bots auto-fill them, humans never see them.
--}}
@php session(['_form_started' => time()]) @endphp

<div style="position:absolute;left:-9999px;top:-9999px;opacity:0;height:0;width:0;overflow:hidden;" aria-hidden="true" tabindex="-1">
    <label for="_hp">Leave this blank</label>
    <input type="text" id="_hp" name="_hp" value="" tabindex="-1" autocomplete="off" aria-hidden="true">
    <label for="website">Website</label>
    <input type="text" id="website" name="website" value="" tabindex="-1" autocomplete="off" aria-hidden="true">
</div>
