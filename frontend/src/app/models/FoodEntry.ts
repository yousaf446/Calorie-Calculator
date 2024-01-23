import { User } from "./User";
import { UserConstraints } from "./UserConstraints";

export class FoodEntry {
    public id: string = "";
    public user_id: string = "";
    public product_name: string = "";
    public consumed_at: Date = new Date();
    public calorie_value: number = 0;
    public price: number = 0;
    public user: User = new User();
    public userConstraints: UserConstraints = new UserConstraints();

    constructor(values: Object = {}) {
      // Constructor initialization
      Object.assign(this, values);
    }
  }