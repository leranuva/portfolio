import React from 'react';
import { Routes, Route } from 'react-router-dom';
import Navigation from './Navigation';
import Home from './Home';
import Footer from './Footer';

const PublicLayout = () => {
  return (
    <>
      <Navigation />
      <Routes>
        <Route path="/" element={<Home />} />
      </Routes>
      <Footer />
    </>
  );
};

export default PublicLayout;

