import React from 'react';
import ReactDOM from 'react-dom';
import DatePick from './Components/DatePick.js';

class CarReservation extends React.Component {
    render() {
        return (
            <div>
                <DatePick />
            </div>
        );
    }
}


ReactDOM.render(<CarReservation />, document.getElementById('car-reservation'));

export default CarReservation;