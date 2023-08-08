import React from 'react';
import '@mobiscroll/react/dist/css/mobiscroll.min.css';
import { Datepicker, localeFr } from '@mobiscroll/react';

function DatePick() {
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
        />
    ); 
}


export default DatePick;