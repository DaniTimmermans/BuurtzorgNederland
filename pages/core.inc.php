<?php
/*************************************************************
	Pagebuilder framework application
	Learning application for VISTA AO JL2 P5
	Created 2019 by e.steens
*************************************************************/
/*
	Contains all functions applicable to all pages
	Is parent classbeing extended for all pages
*/
	/**
	 * Core class.
	 */
	class Core {
		// create new formatte string as UUID
		protected function createUuid() { 
			return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		        // 32 bits for "time_low"
		        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

		        // 16 bits for "time_mid"
		        mt_rand( 0, 0xffff ),

		        // 16 bits for "time_hi_and_version",
		        // four most significant bits holds version number 4
		        mt_rand( 0, 0x0fff ) | 0x4000,

		        // 16 bits, 8 bits for "clk_seq_hi_res",
		        // 8 bits for "clk_seq_low",
		        // two most significant bits holds zero and one for variant DCE1.1
		        mt_rand( 0, 0x3fff ) | 0x8000,

		        // 48 bits for "node"
		        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		    );
		}

		// create random hex string as Hash
		protected function createHash() {
			$bytes = openssl_random_pseudo_bytes(32);
			$hash = bin2hex($bytes);
			return $hash;
		}

		// Create hash string based on current dat/time
		protected function createHashDate($p_iHours = 24) {
			//$p_iHours contains hash date system (default 24)
			$now = new DateTime(); //current date/time
			$now->add(new DateInterval("PT{$p_iHours}H"));
			$new_time = $now->format('Y-m-d H:i:s');
			return $new_time;
		}
	}