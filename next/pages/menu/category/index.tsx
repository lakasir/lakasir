import { useCategory } from "@/hooks/category";
import { ICategoryFormRequest, ICategoryResponse } from "@/models/category";
import { Response } from "@/models/response";
import { Button, FloatingActionButton } from "@/ui/Buttons";
import { Card } from "@/ui/Card";
import { Form, Input } from "@/ui/Fields";
import { Layout } from "@/ui/Layout";
import { Modal } from "@/ui/Modals";
import { PencilIcon, SearchIcon, TrashIcon } from "@heroicons/react/solid";
import { NextPage } from "next";
import { useRouter } from "next/router";
import { FormEvent, useEffect, useState } from "react";

const Category: NextPage = () => {
  const router = useRouter();
  const [errors, setErrors] = useState({
    name: "",
  });
  const [show, setShow] = useState({
    addOrEdit: false,
    search: false,
  });
  const [category, setCategory] = useState({
    name: "",
    id: 0,
  });
  const [categoryData, setCategoryData] = useState<ICategoryResponse[]>([]);
  const { getCategory, createCategory, deleteCategory, getDetailCategory, updateCategory } =
    useCategory();
  const loadData = () => {
    getCategory().then((response) => {
      if (response) {
        const responseData = response as Response<ICategoryResponse[]>;
        setCategoryData(responseData.data);
      }
    });
  };

  useEffect(() => {
    loadData();
  }, [categoryData, errors]);

  const removeCategory = (id: number) => {
    const newCategoryData = categoryData.filter((member) => member.id !== id);
    setCategoryData(newCategoryData);
  };

  const handleCreateOrUpdateCategory = async (
    _: FormEvent,
    values: ICategoryFormRequest
  ) => {
    setErrors({ name: "" });
    if (category.id === 0) {
      await createCategory(values, (errors) => {
        setErrors({
          name: errors.errors.name ? errors.errors.name[0] : "",
        });
      });
    } else {
      await updateCategory(category.id, values, (errors) => {
        console.log(errors);
        setErrors({
          name: errors.errors.name ? errors.errors.name[0] : "",
        });
      });
    }
    setCategory({ name: "", id: 0 });
    setShow({ ...show, addOrEdit: false });
    loadData();
  };

  return (
    <Layout title="Category" back onClick={() => router.push("/menu/product")}>
      <>
        <div className="py-3 space-y-4 mb-24">
          {categoryData.length === 0 ? (
            <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
              No Data
            </div>
          ) : (
            <> </>
          )}
          {categoryData.map((el, index) => (
            <Card
              key={index}
              label={el.name}
              id={el.id}
              action={[
                {
                  icon: <TrashIcon className="w-5 h-5" />,
                  label: "Delete",
                  confirmable: async (confirm) => {
                    if (confirm) {
                      await deleteCategory(el.id);
                      removeCategory(el.id);
                    }
                  },
                },
                {
                  icon: <PencilIcon className="w-5 h-5" />,
                  label: "Edit",
                  onClick: async () => {
                    const detail = await getDetailCategory(el.id);
                    setCategory({
                      name: detail.data.name,
                      id: detail.data.id,
                    });
                    setShow({ ...show, addOrEdit: true });
                  },
                },
              ]}
            />
          ))}
        </div>
        <Modal
          onClose={(status) => {
            setShow({ ...show, addOrEdit: status });
            setCategory({ name: "", id: 0 });
          }}
          open={show.addOrEdit}
        >
          <div className="w-11/12 mx-auto bg-white pb-4 p-2 rounded-md">
            <p className="text-center text-lg py-2 w-full border-b-2 border-b-gray-300">
              {category.id === 0 ? "Add Category" : "Edit Category"}
            </p>
            <Form
              onSubmit={handleCreateOrUpdateCategory}
              initialValue={{ name: category.name }}
            >
              {() => (
                <>
                  <Input
                    name={"name"}
                    type={"text"}
                    error={errors.name}
                    label={
                      <>
                        Name<span className="text-red-500">*</span>
                      </>
                    }
                  />
                  <Button className="w-full py-4">Submit Category</Button>
                </>
              )}
            </Form>
          </div>
        </Modal>
        <FloatingActionButton
          title="Add Category"
          dismissable
          onClick={() => setShow({ ...show, addOrEdit: true })}
          options={[
            {
              label: "Search",
              icon: (
                <SearchIcon className="w-7 h-7 text-white" aria-hidden="true" />
              ),
              onClick: () => alert("Search"),
            },
          ]}
        />
      </>
    </Layout>
  );
};

export default Category;
