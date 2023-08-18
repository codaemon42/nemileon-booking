export const countAvailableSlots = (slotTemplate=new SlotTemplateType()) => {
    let availableSlots = 0;
    slotTemplate.template.rows.forEach(row => {
      row.cols.forEach(col => {
        if(col.show){
          availableSlots += col.available_slots;
        }
      })
    })
    return availableSlots;
  }