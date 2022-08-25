import { NextPage } from "next";
import Image from "next/image";
import { useState } from "react";
import FloatingActionButton from "../../../components/Ui/Buttons/FAB";
import Card from "../../../components/Ui/Card/Card";
import Form from "../../../components/Ui/Fields/Form";
import Input from "../../../components/Ui/Fields/Input";
import Layout from "../../../components/Ui/Layout";

interface IMenuInterface {
  label: string;
  id: number;
}

const category: IMenuInterface[] = [
  {
    id: 1,
    label: "Product A",
  },
];

interface ShowActionInterface {
  delete?: boolean;
  add?: boolean;
}

const Category: NextPage = () => {
  const [show, setShow] = useState<ShowActionInterface>({
    delete: false,
    add: false,
  });

  return (
    <Layout title="Category" back>
      <>
        <div className="py-3 space-y-2 mb-24">
          {category.map((el, index) => (
            <Card
              key={index}
              label={el.label}
              confirmable={() => alert("confirmed")}
              class={{
                confirmable: { confirm: "py-1", cancel: "py-1" },
              }}
              id={el.id}
              action={
                show.delete ? (
                  <div
                    className="bg-gray-200 flex justify-center items-center rounded-r-lg w-1/2 ml-auto h-10"
                    id="action-delete"
                  >
                    <img
                      src={"./../assets/icons/Red Delete.svg"}
                      width="30"
                      height="30"
                    />
                  </div>
                ) : (
                  <></>
                )
              }
            />
          ))}
          <Form
            onSubmit={(e, values) => console.log(values)}
            className="hidden"
            initialValue={{ name: "" }}
          >
            {() => (
              <>
                <Input
                  name={"name"}
                  type={"text"}
                  className="rounded-r-none"
                  label={
                    <>
                      Name<span className="text-red-500">*</span>
                    </>
                  }
                  append={
                    <button
                      type="submit"
                      className="w-1/5 rounded-r-lg bg-gray-200 flex justify-center items-center"
                    >
                      <Image
                        src="/assets/icons/Save.svg"
                        width={30}
                        height={30}
                      />
                    </button>
                  }
                />
              </>
            )}
          </Form>
        </div>
        <FloatingActionButton
          title={show.add ? "Cancel" : "Add Category"}
          dismissable
          onClick={() => {
            if (!show.add) {
              document
                .querySelector("#form__lakasir")
                ?.classList.remove("hidden");
            } else {
              document.querySelector("#form__lakasir")?.classList.add("hidden");
            }
            setShow({ add: !show.add, delete: show.delete });
          }}
          options={[
            {
              label: "Delete",
              icon: (
                <img
                  src={"./../assets/icons/Delete.svg"}
                  width="30"
                  height="30"
                />
              ),
              onClick: () => setShow({ delete: !show.delete, add: show.add }),
            },
          ]}
        />
      </>
    </Layout>
  );
};

export default Category;
