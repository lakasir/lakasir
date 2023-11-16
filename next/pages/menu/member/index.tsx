import { NextPage } from "next";
import Image from "next/image";
import Link from "next/link";
import { useRouter } from "next/router";
import { useState } from "react";
import FloatingActionButton from "../../../components/Ui/Buttons/FAB";
import Card from "../../../components/Ui/Card/Card";
import CardLink from "../../../components/Ui/Card/CardLink";
import Input from "../../../components/Ui/Fields/Input";
import Layout from "../../../components/Ui/Layout";

interface IMenuInterface {
  label: string;
  description: string;
  sub_description: string;
  id: number;
}

const member: IMenuInterface[] = [
  {
    id: 1,
    label: "Member A",
    description: "Description",
    sub_description: "Sub Description",
  },
];

interface ShowActionInterface {
  delete?: boolean;
  add?: boolean;
  search?: boolean;
  edit?: boolean;
}

const Category: NextPage = () => {
  const [show, setShow] = useState<ShowActionInterface>({
    delete: false,
    add: false,
    search: false,
    edit: false,
  });

  return (
    <Layout title="Member" back>
      <>
        <div className="py-3 space-y-2 mb-24">
          {member.map((el, index) => (
            <Link href={`/menu/member/edit/${el.id}`} key={index}>
              <CardLink
                onClick={(e: React.MouseEvent<HTMLAnchorElement>) => {
                  if (show.delete) e.preventDefault();
                }}
              >
                <Card
                  label={el.label}
                  description={el.description}
                  sub_description={el.sub_description}
                  confirmable={() => {
                    if (show.delete) {
                      alert("confirm deleted");
                    }
                    if (show.edit) {
                      alert("confirm edit");
                    }
                  }}
                  class={{ confirmable: { confirm: "py-5", cancel: "py-5" } }}
                  id={el.id}
                  action={
                    <>
                      {show.delete ? (
                        <div
                          className="bg-gray-200 flex justify-center items-center rounded-r-lg w-3/4 ml-auto h-[67px]"
                          id="action-delete"
                        >
                          <img
                            src={"./../assets/icons/Red Delete.svg"}
                            width="50"
                            height="50"
                          />
                        </div>
                      ) : (
                        ""
                      )}
                    </>
                  }
                />
              </CardLink>
            </Link>
          ))}
        </div>
        <FloatingActionButton
          title="Add Member"
          action="/menu/member/add"
          dismissable
          options={[
            {
              label: "Search",
              icon: (
                <img
                  src={"./../assets/icons/Search.svg"}
                  width="30"
                  height="30"
                />
              ),
              onClick: () => setShow({ search: !show.search }),
            },
            {
              label: "Delete",
              icon: (
                <img
                  src={"./../assets/icons/Delete.svg"}
                  width="30"
                  height="30"
                />
              ),
              onClick: () => setShow({ delete: !show.delete }),
            },
          ]}
        />
        {show.search ? (
          <div
            className="z-50 top-0 left-0 fixed h-screen w-screen bg-black opacity-90"
            id="filter-search"
          >
            <div
              className="flex justify-center items-center h-full"
              onClick={(e) => {
                if (!(e.target as Element).matches("input")) {
                  setShow({ search: false });
                }
              }}
            >
              <div className="w-11/12 mx-auto">
                <Input
                  name={"Search"}
                  type={"text"}
                  className="rounded-r-none border-r-0"
                  append={
                    <div
                      className="bg-gray-100 rounded-r-lg w-1/5 flex justify-center items-center cursor-pointer border-2 border-gray-300"
                      onClick={() => alert("ok")}
                    >
                      <img src="./../assets/icons/Search-Red.svg" />
                    </div>
                  }
                />
              </div>
            </div>
          </div>
        ) : (
          ""
        )}
      </>
    </Layout>
  );
};

export default Category;
