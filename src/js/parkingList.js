import $ from 'jquery';

export default getParkingList = async (baseUrl) => {
    
    const parkingListContainer = $('#parkList');

    if(parkingListContainer.length) {

        const itemTemplate = $('#listItem').html();
        let parkings = [];

        const parkingList = await fetch(`${baseUrl}/list`)
                            .then(res => res.json())
        parkingList.parkings.forEach((e,i) => {

            parkingListContainer.append(
                itemTemplate.replace('{{parking-name}}',e.nome)
            );
            parkings.push(e);
        });
        
        $('.parkItem').each((i,e) => {
            let $this = $(e);
            $this.click(() => {
                location.href = `${baseUrl}/park?item=${parkings[i].id}`
            })
        })
    }
}
