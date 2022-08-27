import { PencilIcon } from "@heroicons/react/outline";
import { UserIcon } from "@heroicons/react/solid";
import { NextPage } from "next";
import Link from "next/link";
import Layout from "../../../components/Ui/Layout";

const Profile: NextPage = () => {
  return (
    <Layout
      title="Profile"
      back
      background="[#FF6600]"
      textColor="white"
      append={
        <div>
          <div className="w-screen h-96 bg-lakasir-primary flex justify-center items-center">
            <div>
              <div className="w-40 h-40 bg-white rounded-full flex items-center justify-center">
                <UserIcon className="text-gray-300 w-24 h-24" />
              </div>
              <p className="text-center mt-5 text-2xl text-white font-semibold">
                Profile Name
              </p>
            </div>
            <Link href="/menu/profile/edit">
              <a className="relative -top-10 left-3">
                <div className="absolute">
                  <PencilIcon className="text-white w-7 h-7" />
                </div>
              </a>
            </Link>
          </div>
        </div>
      }
    >
      <>
        <div className="my-7 space-y-5">
          <ul>
            <li>Name</li>
            <li className="font-semibold">Profile Name</li>
          </ul>
          <ul>
            <li>Role User</li>
            <li className="font-semibold">Admin</li>
          </ul>
          <ul>
            <li>Email</li>
            <li className="font-semibold">profilenam@mail.com</li>
          </ul>
          <ul>
            <li>Phone</li>
            <li className="font-semibold">0009999238</li>
          </ul>
          <ul>
            <li>Address</li>
            <li className="font-semibold">
              Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam
            </li>
          </ul>
          <ul>
            <li>Language</li>
            <li className="font-semibold">English</li>
          </ul>
          <hr className="border-2" />
          <div className="text-lakasir-primary font-semibold text-lg text-center">
            Logout
          </div>
        </div>
      </>
    </Layout>
  );
};

export default Profile;
