import $ from 'jquery';

export default handleLoginSbmt = () => {
    const form = $('#loginForm');
    const sbmtBtn = form.find('.formButton');
    sbmtBtn.click(() => {
        form.submit();
    })
}