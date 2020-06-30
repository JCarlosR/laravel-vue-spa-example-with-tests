export function isValidDate(dateString) {
    const regEx = /^\d{4}-\d{2}-\d{2}$/;

    // Invalid format
    if (!dateString.match(regEx)) 
        return false; 
    
    const d = new Date(dateString);
    const dNum = d.getTime();

    // NaN value, Invalid date
    if (!dNum && dNum !== 0) 
        return false;
    
    return d.toISOString().slice(0, 10) === dateString;
}

export function isToday(dateString) {
    const d = new Date(); // today
    
    const todayString = [
        d.getFullYear(),
        ('0' + (d.getMonth() + 1)).slice(-2),
        ('0' + d.getDate()).slice(-2)
    ].join('-');
    
    return todayString === dateString;
}