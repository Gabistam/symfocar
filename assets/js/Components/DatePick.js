import React, { useState } from 'react';
import '@mobiscroll/react/dist/css/mobiscroll.min.css';
import { Datepicker, localeFr } from '@mobiscroll/react';
import axios from 'axios';

function DatePick() {

    const [startDate, setStartDate] = useState(null);
    const [endDate, setEndDate] = useState(null);

    const handleDateChange = (event, inst) => {
        const { start, end } = inst.getVal(); // Récupère les dates de début et de fin
        setStartDate(start);
        setEndDate(end);
    };

    const handleReservation = async () => {
        const data = {
            startDate: startDate.toISOString(),
            endDate: endDate.toISOString()
            // Ajoutez d'autres champs du formulaire au besoin
        };

        try {
            const response = await axios.post('http://localhost:8000/api/reservation', data);
            console.log('Réservation réussie !', response.data);
        } catch (error) {
            console.error('Erreur lors de la réservation:', error.response.data.errors);
        }
    };

    return (
        <div>
            <Datepicker
                locale={localeFr}
                theme="ios" 
                themeVariant="light"
                controls={['calendar']}
                display="inline"
                rangeSelectMode="wizard"
                select="range"
                showRangeLabels={true}
                onSet={handleDateChange}
            />
            <button onClick={handleReservation}>Réserver</button>
        </div>
    ); 
}

export default DatePick;
