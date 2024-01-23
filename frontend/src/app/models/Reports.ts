export class Reports {
    public foodentries_last7days: number = 0;
    public foodentries_7daysbefore: number = 0;
    public avg_calories_added_last7days: number= 0;

    constructor(values: Object = {}) {
      // Constructor initialization
      Object.assign(this, values);
    }
  }