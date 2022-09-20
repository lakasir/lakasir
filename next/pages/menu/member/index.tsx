import { useMember } from "@/hooks/member";
import { IMemberResponse } from "@/models/member";
import { Response } from "@/models/response";
import { FloatingActionButton } from "@/ui/Buttons";
import { Card } from "@/ui/Card";
import { Input } from "@/ui/Fields";
import { Layout } from "@/ui/Layout";
import { PencilIcon, TrashIcon } from "@heroicons/react/solid";
import { NextPage } from "next";
import { useRouter } from "next/router";
import { useEffect, useState } from "react";

interface IMenuInterface {
  label: string;
  description: string;
  sub_description: string;
  id: number;
}

interface ShowActionInterface {
  delete?: boolean;
  add?: boolean;
  search?: boolean;
  edit?: boolean;
}

const Category: NextPage = () => {
  const { getMember, deleteMember } = useMember();
  const [memberData, setMemberData] = useState<IMemberResponse[]>([]);
  const router = useRouter();
  const loadData = () => {
    getMember().then((response) => {
      if (response) {
        const responseData = response as Response<IMemberResponse[]>;
        setMemberData(responseData.data);
      }
    });
  };

  useEffect(() => {
    loadData();
  }, [memberData]);
  // remove memberData by id member from array
  const removeMember = (id: number) => {
    const newMemberData = memberData.filter((member) => member.id !== id);
    setMemberData(newMemberData);
  };

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
          {memberData.length === 0 ? (
            <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
              No Data
            </div>
          ) : (
            <> </>
          )}
          {memberData.map((el, index) => (
            <Card
              key={index}
              onClick={() => {
                router.push(`/menu/member/edit/${el.id}`);
              }}
              label={el.name}
              description={el.name}
              sub_description={el.code}
              id={el.id}
              action={[
                {
                  icon: <TrashIcon className="w-5 h-5" />,
                  label: "Delete",
                  confirmable: (confirm) => {
                    if (confirm) {
                      deleteMember(el.id).then((_) => {
                        removeMember(el.id);
                      });
                    }
                  },
                },
                {
                  icon: <PencilIcon className="w-5 h-5" />,
                  label: "Edit",
                  onClick: () => {
                    router.push(`/menu/member/edit/${el.id}`);
                  },
                },
              ]}
            />
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
