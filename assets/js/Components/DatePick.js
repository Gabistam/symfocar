import React, { useEffect } from 'react';
import '@mobiscroll/react/dist/css/mobiscroll.min.css';
import { Datepicker, localeFr } from '@mobiscroll/react';

function DatePick() {

    const handleDateChange = (event, inst) => {
        const { start, end } = inst.getVal(); // Récupère les dates de début et de fin

        // Met à jour les champs cachés du formulaire avec les nouvelles dates
        document.getElementById('reservation_startDate').value = start.toISOString();
        document.getElementById('reservation_endDate').value = end.toISOString();
    };

    return (
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
    ); 
}

export default DatePick;
