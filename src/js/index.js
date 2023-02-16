import 'bootstrap';
import $ from 'jquery';
import handleLogout from './logout';
import handleLoginSbmt from './login';
import getParkingList from './parkingList';

$(document).ready(() => {

    const baseUrl = window.location.origin;
    
    handleLoginSbmt();
    handleLogout(baseUrl);
    getParkingList(baseUrl);
});
