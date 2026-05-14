import { Router } from 'express';
import KorapayService from '../services/korapay.service.js';
import db from '../db.js';
import protect from '../middleware/auth.middleware.js';

const router = Router();

/**
 * Payment Routes
 * ---------------------------------------------------------
 * Handling the money flow here via Korapay. 
 */

// Kick off a payment
router.post('/initialize', protect, async (req, res) => {
  const { email, amount } = req.body;

  try {
    const reference = `NACOS-${req.user.matric}-${Date.now()}`;
    const paymentData = await KorapayService.initializeTransaction({
      email,
      amount,
      reference,
      full_name: req.user.name,
      metadata: { studentId: req.user.id, matric: req.user.matric }
    });

    // Save transaction to DB as 'pending'
    await db.query(
      'INSERT INTO payments (student_id, amount, reference, status, payment_type) VALUES (?, ?, ?, ?, ?)',
      [req.user.id, amount, reference, 'pending', 'dues']
    );

    // Map Korapay response to what the frontend expects
    const formattedData = {
      ...paymentData,
      data: {
        ...paymentData.data,
        authorization_url: paymentData.data.checkout_url
      }
    };

    res.json(formattedData);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
});

// Verify the payment
router.get('/verify/:reference', protect, async (req, res) => {
  const { reference } = req.params;

  try {
    const verificationData = await KorapayService.verifyTransaction(reference);
    
    // Korapay success status is usually 'success' or check verificationData.data.status
    if (verificationData.status === 'success' && (verificationData.data.status === 'success' || verificationData.data.status === 'captured')) {
      // 1. Update Payment Status
      await db.query('UPDATE payments SET status = "success" WHERE reference = ?', [reference]);

      // 2. Mark Student as "Paid"
      await db.query('UPDATE students SET dues_status = "Paid" WHERE id = ?', [req.user.id]);
      
      // 3. Log Activity
      await db.query('INSERT INTO activities (student_id, type, status) VALUES (?, ?, ?)', [req.user.id, 'Dues Payment', 'Done']);

      console.log(`✅ Payment success for student ${req.user.id}`);
    }

    res.json(verificationData);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
});

// Korapay Webhook (For real-time updates)
router.post('/webhook', async (req, res) => {
  const { event, data } = req.body;
  
  console.log('🔔 Korapay Webhook Received:', event);

  if (event === 'charge.success') {
    const reference = data.reference;
    
    try {
      // Find the payment record
      const [payments] = await db.query('SELECT * FROM payments WHERE reference = ?', [reference]);
      
      if (payments.length > 0 && payments[0].status !== 'success') {
        const studentId = payments[0].student_id;

        // 1. Update Payment Status
        await db.query('UPDATE payments SET status = "success" WHERE reference = ?', [reference]);

        // 2. Mark Student as "Paid"
        await db.query('UPDATE students SET dues_status = "Paid" WHERE id = ?', [studentId]);

        // 3. Log Activity
        await db.query('INSERT INTO activities (student_id, type, status) VALUES (?, ?, ?)', [studentId, 'Dues Payment (Webhook)', 'Done']);

        console.log(`✅ Webhook: Payment success for student ${studentId}`);
      }
    } catch (error) {
      console.error('❌ Webhook Processing Error:', error);
    }
  }

  // Always return 200 to Korapay
  res.status(200).send('Webhook Received');
});


export default router;
