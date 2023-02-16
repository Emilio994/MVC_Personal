import $ from 'jquery';

export default handleLogout = (baseUrl) => {
    
    const confirm = $('#yes');
    const negate = $('#no');

    confirm.click(() => {

        fetch(`${baseUrl}/logout`,{
            method : 'POST',
            mode : 'cors',
            redirect : 'follow'
        })
        .then(res => window.location.href = `${baseUrl}/login`)
        .catch(err => console.log(err));
    });

    negate.click(() => {
        window.location.href = baseUrl;
    })
}