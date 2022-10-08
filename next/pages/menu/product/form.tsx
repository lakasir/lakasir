import { useCategory } from "@/hooks/category";
import { useProduct } from "@/hooks/product";
import { ICategoryResponse } from "@/models/category";
import { IProductFormErrorResponse } from "@/models/product";
import { Response } from "@/models/response";
import { Button } from "@/ui/Buttons";
import { Form, Input, Select } from "@/ui/Fields";
import { FilePicker } from "@/ui/Fields/File";
import Image from "next/image";
import Link from "next/link";
import { FormEvent, useEffect, useState } from "react";

interface IFormProductInterface {
  form?: ProductData;
}

interface ProductData {
  name?: string;
  category?: number;
  stock?: number;
  initial_price?: number;
  selling_price?: number;
  type?: number;
  unit?: number;
}

type ResponseFileUploaded = {
  name: string;
  url: string;
};

const FormProduct = (props: IFormProductInterface) => {
  const { createProduct } = useProduct();
  const { getCategory } = useCategory();
  const [categories, setCategories] = useState<ICategoryResponse[]>([]);
  const [errors, setErrors] = useState<IProductFormErrorResponse>();
  const uploadingFiles = (
    file: File,
    promise: (
      url: string,
      formData: FormData
    ) => Promise<Response<ResponseFileUploaded>>,
    setValue: (value: string) => void
  ) => {
    const formData = new FormData();
    formData.append("file", file);
    promise("/api/temp/upload", formData)
      .then((res) => {
        setValue(res.data.url);
      })
      .catch((err) => {
        console.log(err);
      });
  };

  const getCategories = async () => {
    const res = await getCategory();
    if (res) {
      const data = res as Response<ICategoryResponse[]>;
      setCategories(data.data);
    }
  };

  useEffect(() => {
    getCategories();
  }, [errors]);

  return (
    <Form
      className="space-y-8"
      initialValue={{
        ...props.form,
      }}
      onSubmit={(_: FormEvent, values: any) => {
        createProduct(values, (errors) => {
          setErrors({
            category: errors.errors.category ? errors.errors.category[0] : "",
            name: errors.errors.name ? errors.errors.name[0] : "",
            stock: errors.errors.stock ? errors.errors.stock[0] : "",
            initial_price: errors.errors.initial_price
              ? errors.errors.initial_price[0]
              : "",
            selling_price: errors.errors.selling_price
              ? errors.errors.selling_price[0]
              : "",
            unit: errors.errors.unit ? errors.errors.unit[0] : "",
            type: errors.errors.type ? errors.errors.type[0] : "",
          });
        });
      }}
    >
      {() => (
        <>
          <FilePicker
            multiple
            accept="image/*"
            name="images"
            label={"Upload Image"}
            uploadingFiles={uploadingFiles}
          />
          <Input
            error={errors?.name}
            name={"name"}
            type={"text"}
            label={
              <>
                Name<span className="text-red-500">*</span>
              </>
            }
          />
          <Select
            error={errors?.category}
            name={"category"}
            label={
              <>
                Category<span className="text-red-500">*</span>
              </>
            }
            options={categories.map((category) => ({
              value: category.id,
              label: category.name,
            }))}
            append={
              <Link href={"/menu/category"}>
                <a className="bg-gray-100 border-2 border-gray-200 ml-2 rounded-lg w-1/5 flex justify-center items-center cursor-pointer">
                  <Image src="/assets/icons/Plus.svg" width={30} height={30} />
                </a>
              </Link>
            }
          />
          <Input
            error={errors?.stock}
            name={"stock"}
            type={"number"}
            label={
              <>
                Stock<span className="text-red-500">*</span>
              </>
            }
          />
          <Input
            error={errors?.initial_price}
            name={"initial_price"}
            type={"number"}
            label={
              <>
                Initial Price<span className="text-red-500">*</span>
              </>
            }
          />
          <Input
            error={errors?.selling_price}
            name={"selling_price"}
            type={"number"}
            label={
              <>
                Selling Price<span className="text-red-500">*</span>
              </>
            }
          />
          <Select
            error={errors?.type}
            name={"type"}
            label={
              <>
                Type<span className="text-red-500">*</span>
              </>
            }
            options={[
              { value: "product", label: "Product" },
              { value: "product", label: "Service" },
            ]}
          />
          <Input
            name={"unit"}
            error={errors?.unit}
            type={"text"}
            label="Unit"
          />
          <Button className="w-full py-4">Save</Button>
        </>
      )}
    </Form>
  );
};

export default FormProduct;
