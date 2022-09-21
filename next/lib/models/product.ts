import { ICategoryResponse } from "./category";

export type IProductResponse = {
  id: number;
  category_id: number;
  name: string;
  stock: number;
  initial_price: number;
  selling_price: number;
  unit: string;
  type: string;
  category?: ICategoryResponse;
  created_at: string;
  updated_at: string;
};

export type IproductFormRequest = {
  name: string;
  category: number;
  stock: number;
  initial_price: number;
  selling_price: number;
  type: string;
  unit: string;
};

export type IProductFormResponse = {
}
