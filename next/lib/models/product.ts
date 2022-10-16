import { ICategoryResponse } from "./category";

export type IProductImage = {
  id: number;
  product_id: number;
  name: string;
  url: string;
  created_at: string;
  updated_at: string;
};

export type IProductResponse = {
  id: number;
  images: IProductImage[];
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

type IProductFormImage = {
  name: string;
}

export type IProductFormRequest = {
  images: IProductFormImage[];
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

export type IProductFormErrorResponse = {
  name: string;
  category: string;
  stock: string;
  initial_price: string;
  selling_price: string;
  type: string;
  unit: string;
};

